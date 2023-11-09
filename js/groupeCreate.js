import { ajaxRequest, getCookie } from "./functions.js";

const userKey = getCookie('userKey');

document.addEventListener('DOMContentLoaded', () => {
  // Récupérez l'élément d'entrée et le div de sortie
  const inputField = document.getElementById("inputField");
  const outputDiv = document.getElementById("outputDiv");

  // Écoutez l'événement "input" sur le champ de saisie
  inputField.addEventListener("input", function () {
    // Mettez à jour le contenu du div avec la valeur du champ de saisie
    outputDiv.textContent = inputField.value;
    if (outputDiv.textContent == null || outputDiv.textContent == "") {
      outputDiv.textContent = 'Nom du groupe';
    }
  });

  function changeText() {
    let options = document.getElementsByName("confidentialite");
    let font = "";
    let text = "";
    let textDiv = document.getElementById("confidentialite");
    let propos = document.getElementById("propos");
    let Op = document.getElementById("Option");
    let info = document.getElementById("info-Option");

    for (var i = 0; i < options.length; i++) {
      if (options[i].checked) {
        if (options[i].value === 'Public') {
          font = '<i class="fas fa-globe-americas"></i>';
          text = "Tout le monde peut voir qui est dans le groupe et ce qui est publié";
        } else {
          font = '<i class="fas fa-lock"></i>';
          text = "Seuls les membres peuvent voir qui est dans le groupe et ce qui est publié";
        }
        textDiv.innerHTML = font + ` Groupe (` + options[i].value + `) - 1 membre`;
        propos.innerHTML = 'À propos';
        Op.innerHTML = font + " " + options[i].value;
        info.innerHTML = text;
        break;
      }
    }
  }

  document.querySelector('#public').addEventListener('click', () => {
    changeText();
  });

  document.querySelector('#prive').addEventListener('click', () => {
    changeText();
  });

  document.querySelector('#fileInput').addEventListener('change', (e) => {
    console.log(e.target.value)
    changeBackgroundImage(e.target);
  });

  function createGroup() {
    const groupe = {};
    groupe.name = document.querySelector('#inputField').value;
    groupe.isPrivate = document.querySelector('#prive').checked ? 1 : 0;
    groupe.file = $('#fileInput').prop('files')[0];
    return groupe;
  }

  $('#create').on('click', () => {
    const groupe = createGroup();
    let formData = new FormData();

    formData.append("file", groupe.file);
    formData.append("nom", groupe.name);
    formData.append('isPrivate', groupe.isPrivate);
    formData.append('userKey', userKey);

    $.ajax({
      url: "./server/create_group.php",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,
      dataType: 'json',
      success: function (data) {
        console.log(data);
        window.location = './groupe.php';
      }
    });
  });

  function changeBackgroundImage(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();

      reader.onload = function (e) {
        var imageDiv = document.getElementById('imageDiv');
        imageDiv.style.backgroundImage = 'url(' + e.target.result + ')';
      };

      reader.readAsDataURL(input.files[0]);
    }
  }
});
