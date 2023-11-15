import { getCookie, ajaxRequestFiles, defilerBas, ajaxRequest } from "./functions.js";

const userKey = getCookie("userKey");
let legend;
let openMail=false;
let openPass=false;
$(".confirm-box").hide();

function validerTousLesChamps () {
  validerChamps('bio');
  validerChamps('nom');
  validerChamps('prenom');
  validerChamps('Nemail');
  validerChamps('email');
  validerChamps('mdp');
  validerChamps('Nmdp');
}

validerTousLesChamps();

function validerChamps (inputId) {
  legend = $(`.${inputId}-legend`);
  if($(`#${inputId}`).val().length === 0) {
    legend.css('display','none');
  } else {
    legend.css('display','block');
  }
}
 

export const afficherNotification = (temps, message) => {
  let notification = document.getElementById("notification");
  $(notification).text(message);
  notification.style.top = "50px";
  setTimeout(() => {
    notification.style.top = "-50px";
  }, temps);
};

$(document).ready(() => {
  const updateUser = () => {
    const newUser = {};

    newUser.nom = $("#nom").val();
    newUser.prenom = $("#prenom").val();
    newUser.bio = $("#bio").val();
    newUser.email = $("#Nemail").val();
    newUser.pass = $("#mdp").val();
    newUser.newPass = $("#Nmdp").val();
    newUser.file = $('#inputImage').prop("files")[0];
    
    if (newUser.email === "") {
      newUser.email = $("#email").val();
    }
    return newUser;
  };
  
  const changeDisplay = (tag, NewId, text, dis, c) => {
    $(c).css("display", dis);
    $(tag).text(text);
    $(tag).attr("id", NewId);
    setListeners();
  };

  $("#thumb").click(() => {
    $("#inputImage").click();
  });

  const setListeners = () => {
    $("#Annuler-mdp").on("click", (e) => {
      changeDisplay(
        "#Annuler-mdp",
        "Modif-mdp",
        "Modifier mot de passe",
        "none",
        ".mdp"
      );

      openPass=false;
    });
    $("#Modif-mdp").on("click", (e) => {
      changeDisplay(
        "#Modif-mdp",
        "Annuler-mdp",
        "Annuler mot de passe",
        "block",
        ".mdp"
      );
      openPass=true;
      $("#mdp").val("");
      $("#Nmdp").val("");
      validerChamps("Nmdp");
      validerChamps('mdp');
      defilerBas();
    });
    $("#Annuler-email").on("click", (e) => {
      changeDisplay(
        "#Annuler-email",
        "Modif-email",
        "Modifier email",
        "none",
        ".email"
      );
      openMail=false;
    });
    $("#Modif-email").on("click", (e) => {
      changeDisplay(
        "#Modif-email",
        "Annuler-email",
        "Annuler email",
        "block",
        ".email"
      );
      openMail=true;
      validerChamps("Nemail");
      validerChamps("email");
      defilerBas();

    });
  };

  function deleteAccount() {
    
    $("#del-acc").click((e) => {

      $(".update-form").hide();
      $(".confirm-box").show();
    });

    $(".no-btn").click(() => {
      $(".update-form").show();
      $(".confirm-box").hide();
    });
  }

  function uploadImage() {
    $("#inputImage").change((e) => {
      const file = $(e.target).prop('files')[0];
      const fr = new FileReader();
      fr.readAsDataURL(file)
      fr.onload = () => {
        $('#thumb').attr('src', fr.result);
      };
    });
  }

  $('#Nemail,#bio,#nom,#prenom,#Nmdp,#mdp').keyup((e) => {
    const className = `.${$(e.target).attr('id')}-legend`;
    const legend = $(className);
    if ($(e.target).val().length > 0) {
      legend.css('display', 'block');
    } else {
      legend.css('display', 'none');
    }
  });

  $("#enregistrer").click((e) => {
    let updated = updateUser();
    let data = new FormData();

    data.append("file", updated.file);
    data.append("nom", updated.nom);
    data.append("prenom", updated.prenom);
    data.append("bio", updated.bio);
    data.append("email", updated.email);
    data.append("pass", updated.pass);
    data.append("newPass", updated.newPass);
    data.append("userKey", userKey);

    ajaxRequestFiles(data, "./server/update_user.php", "POST", (u) => {
      u = JSON.parse(u);
      console.log(u);
      if(u) {
        $('.title-name').text(`${u.firstName} ${u.lastName}`);
        $('#nom').val(u.lastName);
        $('#prenom').val(u.firstName);
        $('#bio').val(u.bio);
        $("#email").val(u.email);
        $("#mdp").val(u.passwordHash);
        $("#Nmdp").val(u.passwordHash);
        if(!u.profilePic) {
          u.profilePic = "./profil-default.jpg";
        }
        $("#thumb").attr("src", u.profilePic);
        afficherNotification(2000, "C'est fait!");

        if(openMail) {
          $("#Nemail").val('');
        }
        if(openPass) {
          $("#mdp").val('');
          $('#Nmdp').val('');
        }

        validerTousLesChamps();
      
      }
    });
  });
  setListeners();
  uploadImage();
  deleteAccount();
});
