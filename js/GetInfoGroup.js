import { ajaxRequest, getCookie, viderContainer, partialRefresh } from "./functions.js";

const valeur = document.querySelector('#idGroup');
const userKey = getCookie('userKey');
const kolon = document.querySelector('#idAdmin');
const idAdmin = kolon.getAttribute('idAdmin');
const Kiet = document.querySelector('#idUserOnline')
const Mamow = Kiet.getAttribute('idUser');
document.addEventListener('DOMContentLoaded', () => {
  getProfilMember();
  getDemandeGroup();

  partialRefresh(true, getProfilMember, 10000);
  partialRefresh(true, getDemandeGroup, 10000);

  Gestion();

  let spanMembre = document.getElementById('nbMembre');

  partialRefresh(true, renderDemande, 10000);
  renderMember(spanMembre);
  renderDemande();

});
function Gestion(){
  let divGroup = document.getElementById('groupe');
  let divPub = document.getElementById('pub');
  let divDemande = document.getElementById('demande');
  let divAll = document.getElementById('all-Member');
  let spanMembre = document.getElementById('nbMembre');
  let spanDemande = document.getElementById('nbDemande');

  
  

  let boutonDemande = document.getElementById('btnDemande');

  boutonDemande.addEventListener("click", function () {
    divGroup.style.display = "none";
    divPub.style.display = "none";
    divAll.style.display = "none";
    divDemande.style.display = "block";
  });

  var boutonQuitter = document.querySelector("#Quitter");
  var boutonSupprimer = document.querySelector("#SupprimerGroupe");
  let idGroup = valeur.getAttribute("idGroup");

  if (boutonSupprimer !== null) {
    boutonSupprimer.addEventListener("click", function (e) {

      ajaxRequest("POST", "./server/delete_group.php", { "idGroup": idGroup }, (data) => {
        if (data) {
          window.location = './groupe.php';
        }
      })
    });
  }


  boutonQuitter.addEventListener("click", function (e) {

    ajaxRequest("POST", "./server/delete_member_group.php", { "userKey": userKey, "idGroup": idGroup }, (data) => {
      if (data) {
        renderMember(spanMembre);
      }
    })
  });
  var boutonAll = document.querySelector("#All-Members");
  
  boutonAll.addEventListener("click", function (e) {
    ajaxRequest("POST", "./server/get_all_member_group.php", { "userKey": userKey, "idGroup": idGroup }, (data) => {
      divGroup.style.display = "none";
      divPub.style.display = "none";
      divDemande.style.display = "none";
      divAll.style.display = "block";

      viderContainer('#all-Member');
      renderAlls(data);

      let divMembre = document.querySelectorAll('#bonhomme');

      divMembre.forEach((d) => {
        let boutonExclure = d.querySelector('#exclure-btn');
        if (data.length > 0) {
          if (boutonExclure !== null) {
            let idUser = boutonExclure.getAttribute('iduser');
            let idGroup = valeur.getAttribute("idGroup");
            boutonExclure.addEventListener("click", function (e) {
              ajaxRequest("POST", "./server/exclure_member_group.php", { "idUser": idUser, "idGroup": idGroup }, (data) => {
                if (data) {
                  d.remove();
                  renderMember(spanMembre);
                }
              });
            });
          }
        }
        else {
          $('#all-Member').text('Aucun membre excepté vous');
        }
      });
      if (divMembre.length == 0) {
        $('#all-Member').text('Aucun membre excepté vous');
      }
    })
  });
  if (boutonDemande.style.display !== "none") {

    let divDemand = document.querySelectorAll('#bonhomme');

    if (divDemand !== null) {
      divDemand.forEach((d) => {
        let btnAccept = d.querySelector("#accept-btn");;
        let btnRefuse = d.querySelector('#reject-btn');

        if (btnRefuse !== null) {
          const idUser = btnRefuse.getAttribute('iduser');

          btnAccept.addEventListener("click", function (e) {
            ajaxRequest("POST", "./server/accept_member_demand.php", { "idUser": idUser, "idGroup": idGroup }, (data) => {
              if (data) {
                d.remove();
                renderMember(spanMembre);
                renderDemande(spanDemande);
              }
            })
          });

          btnRefuse.addEventListener("click", function (e) {
            ajaxRequest("POST", "./server/reject_member_demand.php", { "idUser": idUser, "idGroup": idGroup }, (data) => {
              if (data) {
                d.remove();
                renderDemande(spanDemande);
              }
            })
          });
        }
      });
    }
  }
}
function renderMember(spanMembre) {
  let idGroup = valeur.getAttribute("idGroup");
  ajaxRequest("POST", "./server/get_nb_member.php", { 'idGroup': idGroup }, (data) => {
    if (data) {
      spanMembre.textContent = data.nbMember;
    }
  });
}
function renderDemande() {
  let spanDemande = document.getElementById('nbDemande');
  let idGroup = valeur.getAttribute("idGroup");
  ajaxRequest("POST", "./server/get_nb_demande.php", { 'idGroup': idGroup }, (data) => {
    if (data) {
      if (data.nbMember == 0) {
        spanDemande.textContent = "0";
      }
      else {
        spanDemande.textContent = data.nbMember;
      }
      Gestion();
    }
  });
}
function renderAlls(users) {
  users.forEach(u => {
    $('#all-Member').append(renderAll(u))
  });
}
function renderAll(user) {
  let message = "";
  if (Mamow == idAdmin) {
    message = `<button style="width: 80%;margin-bottom: 10px;margin-top: 0px;"class="refuser bouton-accepter" id="exclure-btn" idUser="${user.idUser}" action="reject">Exclure</button>`
  }
  return `<div id="bonhomme" class="gallery" userKey="${user.userKey}">
  <a href="./profil.php?${user.userKey}">
    <div style="background-image:url(${user.profilePic});width: 178px;background-size: cover;background-repeat: no-repeat;background-position: center;height: 260px;
}"></div>
  </a>
  <div style="font-size:18px;" class="desc">${user.firstName} ${user.lastName}</div>
  ${message}
</div>`;
}
function getProfilMember() {
  const idGroup1 = valeur.getAttribute("idGroup");
  ajaxRequest("POST", "./server/get_Members_group.php", { 'idGroup': idGroup1 }, (data) => {
    viderContainer('.image-container');
    renderProfilePics(data);
    Gestion();
  });
}
function renderProfilePics(users) {
  users.forEach(u => {
    $('.image-container').append(renderProfilePic(u))
  });
}
function renderProfilePic(user) {
  return `<div class="profileImage" style="width: 50px;border-radius: 50%; margin-left:-10px;background-image:url(${user.profilePic}); height:50px;" title="${user.firstName} ${user.lastName}"><div>`
}
function getDemandeGroup() {
  const idGroup1 = valeur.getAttribute("idGroup");
  ajaxRequest("POST", "./server/get_demande_group.php", { 'idGroup': idGroup1 }, (data) => {
    if (data.length > 0) {
      viderContainer('#demande');
      renderDemands(data);
      Gestion();
    }
    else {
      $('#demande').text('Aucune demande');
    }
  });
}
function renderDemands(users) {
  users.forEach(u => {
    $('#demande').append(renderDemand(u))
  });
}
function renderDemand(user) {
  return `
<div id="bonhomme" class="gallery" userKey="${user.userKey}">
  <a target="_blank" href="${user.profilePic}">
  <div style="background-image:url(${user.profilePic});width: 178px;background-size: cover;background-repeat: no-repeat;background-position: center;height: 260px;
}"></div>
  </a>
  <div class="desc" style="font-size:18px;">${user.firstName} ${user.lastName}</div>
  <button class="bouton-accepter" id="accept-btn" idUser="${user.idUser}" action="accept">Accepter</button>
  <button style="margin-bottom:10px;" class="refuser bouton-accepter" id="reject-btn" idUser="${user.idUser}" action="reject">Refuser</button>
</div>
`;
}