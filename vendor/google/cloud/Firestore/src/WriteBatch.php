<?php
/**
 * Copyright 2017 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Google\Cloud\Firestore;

use Google\Cloud\Core\ArrayTrait;
use Google\Cloud\Core\DebugInfoTrait;
use Google\Cloud\Core\Timestamp;
use Google\Cloud\Core\TimeTrait;
use Google\Cloud\Core\ValidateTrait;
use Google\Cloud\Firestore\Connection\ConnectionInterface;
use Google\Cloud\Firestore\V1beta1\DocumentTransform\FieldTransform\ServerValue;

/**
 * Enqueue and write multiple mutations to Cloud Firestore.
 *
 * This class may be used directly for multiple non-transactional writes. To
 * run changes in a transaction (with automatic retry/rollback on failure),
 * use {@see Google\Cloud\Firestore\Transaction}. Single modifications can be
 * made using the various methods on {@see Google\Cloud\Firestore\DocumentReference}.
 *
 * Example:
 * ```
 * use Google\Cloud\Firestore\FirestoreClient;
 *
 * $firestore = new FirestoreClient();
 * $batch = $firestore->batch();
 * ```
 */
class WriteBatch
{
    use ArrayTrait;
    use DebugInfoTrait;
    use TimeTrait;
    use ValidateTrait;

    const TYPE_UPDATE = 'update';
    const TYPE_DELETE = 'delete';
    const TYPE_TRANSFORM = 'transform';

    const REQUEST_TIME = ServerValue::REQUEST_TIME;

    /**
     * @var ConnectionInterface
     */
    private $connection;

    /**
     * @var ValueMapper
     */
    private $valueMapper;

    /**
     * @var string
     */
    private $database;

    /**
     * @var string|null
     */
    private $transaction;

    /**
     * @var array
     */
    private $writes = [];

    /**
     * @param ConnectionInterface $connection A connection to Cloud Firestore
     * @param ValueMapper $valueMapper A Value Mapper instance
     * @param string $database The current database
     * @param string|null $transaction The transaction to run commits in.
     *        **Defaults to** `null`.
     */
    public function __construct(ConnectionInterface $connection, $valueMapper, $database, $transaction = null)
    {
        $this->connection = $connection;
        $this->valueMapper = $valueMapper;
        $this->database = $database;
        $this->transaction = $transaction;
    }

    /**
     * Enqueue a document creation.
     *
     * This operation will fail (when committed) if the document already exists.
     *
     * Example:
     * ```
     * $batch->create($documentName, [
     *     'name' => 'John'
     * ]);
     * ```
     *
     * @param DocumentReference|string $document The document to target, either
     *        as a string document name, or DocumentReference object. Please
     *        note that DocumentReferences will be used only for the document
     *        name. Field data must be provided in the `$fields` argument.
     * @param array $fields An array containing fields, where keys are the field
     *        names, and values are field values. Nested arrays are allowed.
     *        Note that unlike {@see Google\Cloud\Firestore\DocumentReference::update()},
     *        field paths are NOT supported by this method.
     * @param array $options Configuration options
     * @return WriteBatch
     * @throws \InvalidArgumentException If `FieldValue::deleteField()` is found in the fields list.
     * @throws \InvalidArgumentException If `FieldValue::serverTimestamp()` is found in an array value.
     */
    public function create($document, array $fields, array $options = [])
    {
        // Record whether the document is empty before any filtering.
        $emptyDocument = count($fields) === 0;

        list ($fields, $sentinels, $flags) = $this->filterFields($fields);

        if ($sentinels[FieldValue::deleteField()]) {
            throw new \InvalidArgumentException('Cannot delete fields when creating a document.');
        }

        if ($flags['timestampInArray']) {
            throw new \InvalidArgumentException(
                'Server Timestamps cannot be used anywhere within a non-associative array value.'
            );
        }

        // Cannot create a document that already exists!
        $precondition = ['exists' => false];

        // Enqueue an update operation if an empty document was provided,
        // or if there are still fields after filtering.
        $transformOptions = [];
        if (!empty($fields) || $emptyDocument) {
            $this->writes[] = $this->createDatabaseWrite(self::TYPE_UPDATE, $document, [
                'fields' => $this->valueMapper->encodeValues($fields),
                'precondition' => $precondition
            ] + $options);
        } else {
            // If no UPDATE mutation is enqueued, we need the precondition applied
            // to the transform mutation.
            $transformOptions = [
                'precondition' => $precondition
            ];
        }

        // Some sentinel values (at the time of writing server timestamps)
        // are implemented using TRANSFORM mutations rather than being embedded
        // in the normal update.
        $this->updateTransforms(
            $document,
            $sentinels,
            $transformOptions
        );

        return $this;
    }

    /**
     * Enqueue a set mutation.
     *
     * Unless `$options['merge']` is set to `true, this method replaces all
     * fields in a Firestore document.
     *
     * Example:
     * ```
     * $batch->set($documentName, [
     *     'name' => 'John'
     * ]);
     *
     * @codingStandardsIgnoreStart
     * @param DocumentReference|string $document The document to target, either
     *        as a string document name, or DocumentReference object. Please
     *        note that DocumentReferences will be used only for the document
     *        name. Field data must be provided in the `$fields` argument.
     * @param array $fields An array containing fields, where keys are the field
     *        names, and values are field values. Nested arrays are allowed.
     *        Note that unlike {@see Google\Cloud\Firestore\WriteBatch::update()},
     *        field paths are NOT supported by this method.
     * @param array $options {
     *     Configuration Options
     *
     *     @type bool $merge If true, unwritten fields will be preserved.
     *           Otherwise, they will be overwritten (removed). **Defaults to**
     *           `false`.
     * }
     * @return WriteBatch
     * @codingStandardsIgnoreEnd
     * @throws \InvalidArgumentException If `FieldValue::deleteField()` is found in the document when `$options.merge`
     *         is not `true`.
     * @throws \InvalidArgumentException If `FieldValue::serverTimestamp()` is found in an array value.
     */
    public function set($document, array $fields, array $options = [])
    {
        $merge = $this->pluck('merge', $options, false) ?: false;

        // Record whether the document is empty before any filtering.
        $emptyDocument = count($fields) === 0;

        list ($fields, $sentinels, $flags) = $this->filterFields($fields);

        if (!$merge && $sentinels[FieldValue::deleteField()]) {
            throw new \InvalidArgumentException('Delete cannot appear in data unless `$options[\'merge\']` is set.');
        }

        if ($flags['timestampInArray']) {
            throw new \InvalidArgumentException(
                'Server Timestamps cannot be used anywhere within a non-associative array value.'
            );
        }

        $hasOnlyTimestamps = count($fields) === 0
            && !$emptyDocument
            && $sentinels[FieldValue::serverTimestamp()]
            && !$sentinels[FieldValue::deleteField()];

        // Enqueue a write if any of the following conditions are met
        // - if there are still fields remaining after sentinels were removed
        // - if the user provided an empty set to begin with
        // - if the user provided only server timestamp sentinel values AND did not specify merge behavior
        // - if the user provided only delete sentinel field values.
        $shouldEnqueueUpdate = $fields
            || $emptyDocument
            || ($hasOnlyTimestamps && !$merge)
            || $sentinels[FieldValue::deleteField()];

        if ($shouldEnqueueUpdate) {
            $write = [
                'fields' => $this->valueMapper->encodeValues($fields),
            ];

            if ($merge) {
                $deletes = $sentinels[FieldValue::deleteField()];

                $write['updateMask'] = $this->pathsToStrings(
                    array_merge($this->encodeFieldPaths($fields), $deletes)
                );
            }

            $this->writes[] = $this->createDatabaseWrite(self::TYPE_UPDATE, $document, $write, $options);
        }

        // Some sentinel values (at the time of writing server timestamps)
        // are implemented using TRANSFORM mutations rather than being embedded
        // in the normal update.
        $this->updateTransforms($document, $sentinels, $options);

        return $this;
    }

    /**
     * Enqueue an update with field paths and values.
     *
     * Merges provided data with data stored in Firestore.
     *
     * Calling this method on a non-existent document will raise an exception.
     *
     * This method supports various sentinel values, to perform special operations
     * on fields. Available sentinel values are provided as methods, found in
     * {@see Google\Cloud\Firestore\FieldValue}.
     *
     * Note that field names must be provided using field paths, encoded either
     * as a dot-delimited string (i.e. `foo.bar`), or an instance of
     * {@see Google\Cloud\Firestore\FieldPath}. Nested arrays are not allowed.
     *
     * Please note that conflicting paths will result in an exception. Paths
     * conflict when one path indicates a location nested within another path.
     * For instance, path `a.b` cannot be set directly if path `a` is also
     * provided.
     *
     * Example:
     * ```
     * $batch->update($documentName, [
     *     ['path' => 'name', 'value' => 'John'],
     *     ['path' => 'country', 'value' => 'USA'],
     *     ['path' => 'cryptoCurrencies.bitcoin', 'value' => 0.5],
     *     ['path' => 'cryptoCurrencies.ethereum', 'value' => 10],
     *     ['path' => 'cryptoCurrencies.litecoin', 'value' => 5.51]
     * ]);
     * ```
     *
     * ```
     * // Google Cloud PHP provides special field values to enable operations such
     * // as deleting fields or setting the value to the current server timestamp.
     * use Google\Cloud\Firestore\FieldValue;
     *
     * $batch->update($documentName, [
     *     ['path' => 'country', 'value' => FieldValue::deleteField()],
     *     ['path' => 'lastLogin', 'value' => FieldValue::serverTimestamp()]
     * ]);
     * ```
     *
     * ```
     * // If your field names contain special characters (such as `.`, or symbols),
     * // using {@see Google\Cloud\Firestore\FieldPath} will properly escape each element.
     *
     * use Google\Cloud\Firestore\FieldPath;
     *
     * $batch->update($documentName, [
     *     ['path' => new FieldPath(['cryptoCurrencies', 'big$$$coin']), 'value' => 5.51]
     * ]);
     * ```
     *
     * @param DocumentReference|string $document The document to target, either
     *        as a string document name, or DocumentReference object. Please
     *        note that DocumentReferences will be used only for the document
     *        name. Field data must be provided in the `$data` argument.
     * @param array[] $data A list of arrays of form
     *        `[FieldPath|string $path, mixed $value]`.
     * @param array $options Configuration options
     * @return WriteBatch
     * @throws \InvalidArgumentException If data is given in an invalid format
     *         or is empty.
     * @throws \InvalidArgumentException If any field paths are empty.
     * @throws \InvalidArgumentException If field paths conflict.
     */
    public function update($document, array $data, array $options = [])
    {
        if (!$data || $this->isAssoc($data)) {
            throw new \InvalidArgumentException(
                'Field data must be provided as a list of arrays of form `[string|FieldPath $path, mixed $value]`.'
            );
        }

        $paths = [];
        $fields = [];
        foreach ($data as $field) {
            $this->arrayHasKeys($field, ['path', 'value']);

            $path = ($field['path'] instanceof FieldPath)
                ? $field['path']
                : FieldPath::fromString($field['path']);

            if (!$path->path()) {
                throw new \InvalidArgumentException('Field Path cannot be empty.');
            }

            $paths[] = $path;

            $keys = $path->path();
            $num = count($keys);

            // Construct a nested array to represent a nested field path.
            // For instance, `a.b.c` = 'foo' will become
            // `['a' => ['b' => ['c' => 'foo']]]`
            $val = $field['value'];
            foreach (array_reverse($keys) as $index => $key) {
                if ($num >= $index + 1) {
                    $val = [
                        $key => $val
                    ];
                }
            }

            $fields = $this->arrayMergeRecursive($fields, $val);
        }

        if (count(array_unique($paths)) !== count($paths)) {
            throw new \InvalidArgumentException('Duplicate field paths are not allowed.');
        }

        // Record whether the document is empty before any filtering.
        $emptyDocument = count($fields) === 0;

        list ($fields, $sentinels, $flags) = $this->filterFields($fields);

        // to conform to specification.
        if (isset($options['precondition']['exists'])) {
            throw new \InvalidArgumentException('Exists preconditions are not supported by this method.');
        }

        if ($flags['timestampInArray']) {
            throw new \InvalidArgumentException(
                'Server Timestamps cannot be used anywhere within a non-associative array value.'
            );
        }

        // We only want to enqueue an update write if there are non-sentinel fields
        // OR no timestamp sentinels are found.
        // We MUST always enqueue at least one write, so if there are no fields
        // and no timestamp sentinels, we can assume an empty write is intended
        // and enqueue an empty UPDATE operation.
        $shouldEnqueueUpdate = $fields
            || !$sentinels[FieldValue::serverTimestamp()]
            || $sentinels[FieldValue::deleteField()];

        if ($shouldEnqueueUpdate) {
            $write = [
                'fields' => $this->valueMapper->encodeValues($fields),
            ];

            $deletes = $sentinels[FieldValue::deleteField()];
            $timestamps = $sentinels[FieldValue::serverTimestamp()];

            // Add deletes to the list of paths
            $mask = array_merge($paths, $deletes);
            $mask = array_unique($this->pathsToStrings($mask));

            // Check the update mask for prefix paths.
            // This needs to happen before we remove server timestamp sentinels.
            $this->checkPrefixes($mask);

            // remove timestamps from the mask.
            // since we constructed the input mask before removing sentinels,
            // we'll need to run through and pull them out now.
            $mask = array_filter($mask, function ($item) use ($timestamps) {
                return !in_array($item, $timestamps);
            });

            $write['updateMask'] = $mask;

            $this->writes[] = $this->createDatabaseWrite(
                self::TYPE_UPDATE,
                $document,
                $write + $this->formatPrecondition($options, true)
            );
        } else {
            // If no update write is enqueued, preconditions must be applied to
            // a transform.
            $options = $this->formatPrecondition($options, true);
        }

        // Setting values to the server timestamp is implemented as a document tranformation.
        $this->updateTransforms($document, $sentinels, $options);

        return $this;
    }

    /**
     * Delete a Firestore document.
     *
     * Example:
     * ```
     * $batch->delete($documentName);
     * ```
     *
     * @codingStandardsIgnoreStart
     * @param DocumentReference|string $document The document to target, either
     *        as a string document name, or DocumentReference object.
     * @param array $options Configuration Options
     * @return WriteBatch
     * @codingStandardsIgnoreEnd
     */
    public function delete($document, array $options = [])
    {
        $options = $this->formatPrecondition($options);
        $this->writes[] = $this->createDatabaseWrite(self::TYPE_DELETE, $document, $options);

        return $this;
    }

    /**
     * Commit writes to the database.
     *
     * Example:
     * ```
     * $batch->commit();
     * ```
     *
     * @codingStandardsIgnoreStart
     * @see https://firebase.google.com/docs/firestore/reference/rpc/google.firestore.v1beta1#google.firestore.v1beta1.Firestore.Commit Commit
     *
     * @param array $options Configuration Options
     * @return array [https://firebase.google.com/docs/firestore/reference/rpc/google.firestore.v1beta1#commitresponse](CommitResponse)
     * @codingStandardsIgnoreEnd
     */
    public function commit(array $options = [])
    {
        unset($options['merge'], $options['precondition']);

        $response = $this->connection->commit(array_filter([
            'database' => $this->database,
            'writes' => $this->writes,
            'transaction' => $this->transaction
        ]) + $options);

        if (isset($response['commitTime'])) {
            $time = $this->parseTimeString($response['commitTime']);
            $response['commitTime'] = new Timestamp($time[0], $time[1]);
        }

        if (isset($response['writeResults'])) {
            foreach ($response['writeResults'] as &$result) {
                if (isset($result['updateTime'])) {
                    $time = $this->parseTimeString($result['updateTime']);
                    $result['updateTime'] = new Timestamp($time[0], $time[1]);
                }
            }
        }

        return $response;
    }

    /**
     * Rollback a transaction.
     *
     * If the class was created without a Transaction ID, this method will fail.
     *
     * This method is intended for use internally and should not be considered
     * part of the public API.
     *
     * @access private
     * @param array $options Configuration Options
     * @return void
     * @throws \RuntimeException If no transaction ID is provided at class construction.
     */
    public function rollback(array $options = [])
    {
        if (!$this->transaction) {
            throw new \RuntimeException('Cannot rollback because no transaction id was provided.');
        }

        $this->connection->rollback([
            'database' => $this->database,
            'transaction' => $this->transaction
        ] + $options);
    }

    /**
     * Check if the WriteBatch has any writes enqueued.
     *
     * @return bool
     * @access private
     */
    public function isEmpty()
    {
        return ! (bool) $this->writes;
    }

    /**
     * Enqueue transforms for sentinels found in UPDATE calls.
     *
     * @param DocumentReference|string $document The document to target, either
     *        as a string document name, or DocumentReference object.
     * @param array $sentinels
     * @param array $options
     * @return void
     */
    private function updateTransforms($document, array $sentinels, array $options = [])
    {
        $transforms = [];
        foreach ($sentinels[FieldValue::serverTimestamp()] as $timestamp) {
            $transforms[] = [
                'fieldPath' => $timestamp->pathString(),
                'setToServerValue' => self::REQUEST_TIME
            ];
        }

        if ($transforms) {
            $document = ($document instanceof DocumentReference)
                ? $document->name()
                : $document;

            $this->writes[] = $this->createDatabaseWrite(self::TYPE_TRANSFORM, $document, [
                'fieldTransforms' => $transforms
            ] + $options);
        }
    }

    /**
     * @param string $type The write operation type.
     * @param DocumentReference|string $document The document to target, either
     *        as a string document name, or DocumentReference object.
     * @param array $options {
     *     Configuration Options.
     *
     *     @type array $updateMask A list of field paths to update in this document.
     *     @type array $currentDocument An optional precondition.
     *     @type array $fields An array of document fields and their values. Required
     *           if $type is `update`.
     * }
     * @return array
     */
    private function createDatabaseWrite($type, $document, array $options = [])
    {
        $mask = $this->pluck('updateMask', $options, false);
        if ($mask !== null) {
            sort($mask);
            $mask = ['fieldPaths' => $mask];
        }

        $document = ($document instanceof DocumentReference)
            ? $document->name()
            : $document;

        return $this->arrayFilterRemoveNull([
            'updateMask' => $mask,
            'currentDocument' => $this->validatePrecondition($options),
        ]) + $this->createDatabaseWriteOperation($type, $document, $options);
    }

    /**
     * Validates a document precondition, if set.
     *
     * @codingStandardsIgnoreStart
     * @param array $options Configuration Options
     * @return array [Precondition](https://firebase.google.com/docs/firestore/reference/rpc/google.firestore.v1beta1#google.firestore.v1beta1.Precondition)
     * @throws \InvalidArgumentException If the precondition is invalid.
     * @codingStandardsIgnoreEnd
     */
    private function validatePrecondition(array &$options)
    {
        $precondition = isset($options['precondition'])
            ? $options['precondition']
            : null;

        if (!$precondition) {
            return;
        }

        if (isset($precondition['exists'])) {
            return $precondition;
        }

        if (isset($precondition['updateTime'])) {
            if (!($precondition['updateTime'] instanceof Timestamp)) {
                throw new \InvalidArgumentException(
                    'Precondition Update Time must be an instance of `Google\\Cloud\\Core\\Timestamp`'
                );
            }

            return [
                'updateTime' => $precondition['updateTime']->formatForApi()
            ];
        }

        throw new \InvalidArgumentException('Preconditions must provide either `exists` or `updateTime`.');
    }

    /**
     * Create the write operation object.
     *
     * @param string $type The write type.
     * @param string $document The document to target, either
     *        as a string document name, or DocumentReference object.
     * @param array $options Configuration Options.
     * @return array
     * @throws \InvalidArgumentException If $type is not a valid value.
     */
    private function createDatabaseWriteOperation($type, $document, array $options = [])
    {
        switch ($type) {
            case self::TYPE_UPDATE:
                return [
                    'update' => [
                        'name' => $document,
                        'fields' => $this->pluck('fields', $options)
                    ]
                ];
                break;

            case self::TYPE_DELETE:
                return ['delete' => $document];
                break;

            case self::TYPE_TRANSFORM:
                return [
                    'transform' => [
                        'document' => $document,
                        'fieldTransforms' => $this->pluck('fieldTransforms', $options)
                    ]
                ];
                break;

            // @codeCoverageIgnoreStart
            default:
                throw new \InvalidArgumentException(sprintf(
                    'Write operation type `%s is not valid. Allowed values are update, delete, verify, transform.',
                    $type
                ));
                break;
            // @codeCoverageIgnoreEnd
        }
    }

    /**
     * Filter fields, removing sentinel values from the field list and recording
     * their location.
     *
     * @param array $fields The fields input
     * @return array An array containing the output fields at position 0 and the
     *         sentinels list at position 1.
     */
    private function filterFields(array $fields)
    {
        // initialize the sentinels list with empty arrays.
        $sentinels = [];
        foreach (FieldValue::sentinelValues() as $sentinel) {
            $sentinels[$sentinel] = [];
        }

        // Track useful information here to keep complexity low elsewhere.
        $flags = [
            'timestampInArray' => false
        ];

        $filterSentinels = function (
            array $fields,
            FieldPath $path = null,
            $inArray = false
        ) use (
            &$sentinels,
            &$filterSentinels,
            &$flags
        ) {
            if (!$path) {
                $path = new FieldPath([]);
            }

            foreach ($fields as $key => $value) {
                $currentPath = $path->child($key);
                if (is_array($value)) {
                    // If a sentinel appears in or descends from an array, we need to track that.
                    $localInArray = $inArray
                        ? $inArray
                        : !$this->isAssoc($value);

                    $fields[$key] = $filterSentinels($value, $currentPath, $localInArray);
                    if (empty($fields[$key])) {
                        unset($fields[$key]);
                    }
                } else {
                    if (FieldValue::isSentinelValue($value)) {
                        $sentinels[$value][] = $currentPath;
                        if ($value === FieldValue::serverTimestamp() && $inArray) {
                            $flags['timestampInArray'] = true;
                        }

                        unset($fields[$key]);
                    }
                }
            }

            return $fields;
        };

        $fields = $filterSentinels($fields);

        return [$fields, $sentinels, $flags];
    }

    /**
     * Check list of FieldPaths for prefix paths and throw exception.
     *
     * @param string[] $paths
     * @throws \InvalidArgumentException If prefix paths are found.
     */
    private function checkPrefixes(array $paths)
    {
        sort($paths);

        for ($i = 1; $i < count($paths); $i++) {
            if ($this->isPrefix($paths[$i-1], $paths[$i])) {
                throw new \InvalidArgumentException(sprintf(
                    'Field path conflict detected for field path `%s`. ' .
                    'Conflicts occur when a field path descends from another ' .
                    'path. For instance `a.b` is not allowed when `a` is also ' .
                    'provided.',
                    $paths[$i-1]
                ));
            }
        }
    }

    /**
     * Compare two field paths to determine whether one is a prefix of the other.
     *
     * @param string $prefix The prefix path.
     * @param string $suffix The suffix path.
     * @return bool
     */
    private function isPrefix($prefix, $suffix)
    {
        $prefix = explode('.', $prefix);
        $suffix = explode('.', $suffix);

        return count($prefix) < count($suffix)
            && $prefix === array_slice($suffix, 0, count($prefix));
    }

    /**
     * Correctly formats a precondition for a write.
     *
     * @param array $options Configuration options input.
     * @param bool $mustExist If true, the precondition will always include at
     *        least `exists=true` precondition. **Defaults to** `false`.
     * @return array Modified configuration options.
     */
    private function formatPrecondition(array $options, $mustExist = false)
    {
        if (!isset($options['precondition']) && !$mustExist) {
            return $options;
        }

        $precondition = isset($options['precondition'])
            ? $options['precondition']
            : [];

        if (isset($precondition['updateTime'])) {
            return $options;
        }

        if ($mustExist) {
            $precondition['exists'] = true;
        }

        $options['precondition'] = $precondition;

        return $options;
    }

    /**
     * Create a list of fields paths from field data.
     *
     * The return value of this method does not include the field values. It
     * merely provides a list of field paths which were included in the input.
     *
     * @param array $fields A list of fields to map as paths.
     * @param FieldPath|null $path The parent path (used internally).
     * @return FieldPath[]
     */
    private function encodeFieldPaths(array $fields, $path = null)
    {
        $output = [];

        if (!$path) {
            $path = new FieldPath([]);
        }

        foreach ($fields as $key => $val) {
            $currentPath = $path->child($key);

            if (is_array($val) && $this->isAssoc($val)) {
                $output = array_merge(
                    $output,
                    $this->encodeFieldPaths($val, $currentPath)
                );
            } else {
                $output[] = $currentPath;
            }
        }

        return $output;
    }

    /**
     * Convert a set of {@see Google\Cloud\Firestore\FieldPath} objects to strings.
     *
     * @param FieldPath[] $paths The input paths.
     * @return string[]
     */
    private function pathsToStrings(array $paths)
    {
        $out = [];
        foreach ($paths as $path) {
            $out[] = $path->pathString();
        }

        return $out;
    }
}
