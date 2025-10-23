<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Document</title>
</head>
<body>
<script
  src="https://code.jquery.com/jquery-3.5.1.min.js"
  integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
  crossorigin="anonymous"></script>
  <!-- The core Firebase JS SDK is always required and must be listed first -->
<script src="https://www.gstatic.com/firebasejs/7.15.0/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.15.0/firebase-firestore.js"></script>


<!-- TODO: Add SDKs for Firebase products that you want to use
     https://firebase.google.com/docs/web/setup#available-libraries -->
<script src="https://www.gstatic.com/firebasejs/7.15.0/firebase-analytics.js"></script>

<script>
  // Your web app's Firebase configuration
  var firebaseConfig = {
    apiKey: "AIzaSyCR64HFUNyNh9fT7-Rycegef81fGSiUfp4",
    authDomain: "chatempresa-68899.firebaseapp.com",
    databaseURL: "https://chatempresa-68899.firebaseio.com",
    projectId: "chatempresa-68899",
    storageBucket: "chatempresa-68899.appspot.com",
    messagingSenderId: "953405251052",
    appId: "1:953405251052:web:494ab2cccc27a67bc3f4c9",
    measurementId: "G-VHQ24WEM7Y"
  };
  // Initialize Firebase
  firebase.initializeApp(firebaseConfig);
  firebase.analytics();

  var db = firebase.firestore();

  $.get("<?php echo $urlMensajes ?>", function(data) {
    console.log(data);
    return false;
    for (i in data.messages){

      db.collection(data.messages[i].chatId).add(data.messages[i])
      .then(function(docRef) {
          console.log("Document written with ID: ", docRef.id);
      })
      .catch(function(error) {
          console.error("Error adding document: ", error);
      });

      console.log(data.messages[i])
    }
  },"json");

</script>
</body>
</html>