<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>botest</title>
</head>
<link href="https://fonts.googleapis.com/css?family=Kanit" rel="stylesheet">
<!-- <link rel="stylesheet" href="css/stylecss_pploy.css" /> -->
<link rel="stylesheet" href="{{URL::asset('css/disc.css')}}">
<style>
#sendmessagebutton {
    background-color: #4CAF50; /* Green */
    border: none;
    color: white;
    padding: 10px 25px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
}


#sendmessagebutton {border-radius: 4px;}

</style>
<body>           
    <div class="content card">
    <!--   <center> <h2> Disclaimer </h2></center>  -->
        <h4 align = 'left'>คำสงวนสิทธิ์</h4>
        <h6 align = 'left'>ทาง REMI จัดทำแชทบอตนี้ เพื่อให้ข้อมูลเกี่ยวกับการดูแลสุขภาพของคุณแม่ตลอดช่วงการตั้งครรภ์ คอยติดตามน้ำหนักและข้อมูลของทารกในแต่ละสัปดาห์ แนะนำด้านอาหารตามหลักโภชนาการ การออกกำลังกายในท่าต่างๆตามช่วงอายุครรภ์ และสามารถตอบคำถามที่คุณแม่อยากทราบในขณะตั้งครรภ์ได้ เพื่อช่วยให้คุณแม่ที่อยู่ในช่วงตั้งครรภ์สามารถมีผู้ดูแลใกล้ตัวได้สะดวก
 ไม่ว่าในขณะใดขณะหนึ่งก็ตาม REMI สามารถทำการแก้ไขปรับปรุงข้อมูลต่าง ๆ ที่ปรากฏแชทบอตนี้ได้</h6>
        <h4 align = 'left'>ข้อจำกัดความรับผิด</h4>
        <h6 align = 'left'>REMI ไม่มีความรับผิดในความเสียหายใด ๆ รวมตลอดถึงความเสียหายทางตรงความเสียหายทางอ้อม ความเสียหายพิเศษ ความเสียหายโดยบังเอิญ หรือความเสียหายเกี่ยวเนื่อง</h6><br>


        <button id="sendmessagebutton" >ยอมรับ</button></center>
                <div class="title m-b-md">
                   <div id="chartdiv"></div>
                </div>   
        
    </div>
    <button id="openwindowbutton" style="display:none" style="display:none" >open window</button>
    <button id="openwindowexternalbutton" style="display:none">open window external</button>
    <div id="profileinfo" style="display:none">
        <h2>Profile</h2>
        <div id="profilepicturediv">
        </div>
        <table border="1">
            <tr>
                <th>userId</th>
                <td id="useridprofilefield"></td>
            </tr>
            <tr>
                <th>displayName</th>
                <td id="displaynamefield"></td>
            </tr>
            <tr>
                <th>statusMessage</th>
                <td id="statusmessagefield"></td>
            </tr>
        </table>
    </div> 
    <div id="liffdata" style="display:none">
        <h2>LIFF Data</h2>
        <table border="1">
            <tr>

                <th>language</th>
                <td id="languagefield"></td>
            </tr>
            <tr>
                <th>context.viewType</th>
                <td id="viewtypefield"></td>
            </tr>
            <tr>
                <th>context.userId</th>
                <td id="useridfield"></td>
            </tr>
            <tr>
                <th>context.utouId</th>
                <td id="utouidfield"></td>
            </tr>
            <tr>
                <th>context.roomId</th>
                <td id="roomidfield"></td>
            </tr>
            <tr>
                <th>context.groupId</th>
                <td id="groupidfield"></td>
            </tr>
        </table>
    </div>
    <!-- LIFF SDK  -->
    <script src="https://d.line-scdn.net/liff/1.0/sdk.js"></script>
    <script >
      var user_id_line;
      window.onload = function (e) {

       
    // init 
    // https://developers.line.me/ja/reference/liff/#initialize-liff-app
    liff.init(function (data) {
        getProfile();
        initializeApp(data);
    });


    // https://developers.line.me/ja/reference/liff/#liffopenwindow()
    document.getElementById('openwindowbutton').addEventListener('click', function () {
        liff.openWindow({
            url: 'https://line.me'
        });
    });

    document.getElementById('openwindowexternalbutton').addEventListener('click', function () {
        liff.openWindow({
            url: 'https://line.me',
            external: true
        });
    });


    document.getElementById('sendmessagebutton').addEventListener('click', function () {



        //https://developers.line.me/ja/reference/liff/#liffsendmessages()
        liff.sendMessages([{
            type: 'text',
            text: 'ยอมรับ'
        }]).then(() => {
            liff.closeWindow();
        })
        //.
        // then(function () {
        //     window.alert("ยืนยันคุณหมอประจำตัวคุณแม่");
        //})
        .catch(function (error) {
            window.alert("Error sending message: " + error);
        });
    });
};

function getProfile(){
    // https://developers.line.me/ja/reference/liff/#liffgetprofile()
    liff.getProfile().then(function (profile) {
        document.getElementById('useridprofilefield').textContent = profile.userId;
        document.getElementById('displaynamefield').textContent = profile.displayName;

        user_id_line =  profile.userId;

        var profilePictureDiv = document.getElementById('profilepicturediv');
        if (profilePictureDiv.firstElementChild) {
            profilePictureDiv.removeChild(profilePictureDiv.firstElementChild);
        }
        var img = document.createElement('img');
        img.src = profile.pictureUrl;
        img.alt = "Profile Picture";
        img.width = 200;
        profilePictureDiv.appendChild(img);

        document.getElementById('statusmessagefield').textContent = profile.statusMessage;
    }).catch(function (error) {
        window.alert("Error getting profile: " + error);
    });
}

function initializeApp(data) {
    document.getElementById('languagefield').textContent = data.language;
    document.getElementById('viewtypefield').textContent = data.context.viewType;
    document.getElementById('useridfield').textContent = data.context.userId;
    document.getElementById('utouidfield').textContent = data.context.utouId;
    document.getElementById('roomidfield').textContent = data.context.roomId;
    document.getElementById('groupidfield').textContent = data.context.groupId;
}



</script>

</body>
</html>