import { ajaxRequest, getCookie, viderContainer } from "./functions.js";

const valeur = document.querySelector('#idGroup');
const userKey = getCookie('userKey');
const kolon = document.querySelector('#idAdmin');
const idAdmin = kolon.getAttribute('idAdmin');
const Kiet = document.querySelector('#idUserOnline')
const Mamow = Kiet.getAttribute('idUser');
document.addEventListener('DOMContentLoaded', () => {
  getProfilMember();
  getDemandeGroup();
  

  let divGroup = document.getElementById('groupe');
  let divPub = document.getElementById('pub');
  let divDemande = document.getElementById('demande');
  let divAll = document.getElementById('all-Member');
  let spanMembre = document.getElementById('nbMembre');
  let spanDemande = document.getElementById('nbDemande');
  
  renderMember(spanMembre);
  renderDemande(spanDemande);

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

  if(boutonSupprimer !== null){
    boutonSupprimer.addEventListener("click", function (e) {

      ajaxRequest("POST", "./server/delete_group.php", {"idGroup": idGroup }, (data) => {
        if (data) {
          console.log(data);
          window.location = './groupe.php';
        }
      })
    });
  }
  

  boutonQuitter.addEventListener("click", function (e) {

    ajaxRequest("POST", "./server/delete_member_group.php", { "userKey": userKey, "idGroup": idGroup }, (data) => {
      if (data) {
        console.log(data);
        renderMember(spanMembre);
      }
    })
  });
  var boutonAll = document.querySelector("#All-Members");
  console.log(boutonAll);
  boutonAll.addEventListener("click", function (e) {
    console.log(idAdmin);
    ajaxRequest("POST", "./server/get_all_member_group.php", { "userKey": userKey, "idGroup": idGroup }, (data) => {
      divGroup.style.display = "none";
      divPub.style.display = "none";
      divDemande.style.display = "none";
      divAll.style.display = "block";

      viderContainer('#all-Member');
      renderAlls(data);

      let boutonExclure = document.getElementById('exclure-btn');
      console.log(boutonExclure);
      let divMembre = document.getElementById('bonhomme');
      
      if (data.length > 0) {
        if(boutonExclure !== null)
        {
          let idUser = boutonExclure.getAttribute('iduser');
          let idGroup = valeur.getAttribute("idGroup");
          boutonExclure.addEventListener("click", function (e){
            ajaxRequest("POST", "./server/exclure_member_group.php", { "idUser": idUser, "idGroup": idGroup }, (data) => {
             if(data){
                console.log(data);
                divMembre.remove();
                renderMember(spanMembre);
              }
            });
          });
        }
        
        
      }
      else {
        $('#all-Member').text('Aucun membre outre vous');
      }
    })
  });
     
  

  if (boutonDemande.style.display !== "none") {
    let boutonAccept = document.getElementById('accept-btn');
    let boutonRefuse = document.getElementById('reject-btn');
    let DivDemand = document.getElementById('bonhomme');
    if (boutonRefuse !== null) {
      const idUser = boutonRefuse.getAttribute('iduser');

      boutonAccept.addEventListener("click", function (e) {

        ajaxRequest("POST", "./server/accept_member_demand.php", { "idUser": idUser, "idGroup": idGroup }, (data) => {
          if (data) {
            console.log(data);
            DivDemand.remove();
            renderMember(spanMembre);
            renderDemande(spanDemande);
          }
        })
      });
      boutonRefuse.addEventListener("click", function (e) {

        ajaxRequest("POST", "./server/reject_member_demand.php", { "idUser": idUser, "idGroup": idGroup }, (data) => {
          if (data) {
            console.log(data);
            DivDemand.remove();
            renderDemande(spanDemande);
          }
        })
      });
    }
  }


});
function renderMember(spanMembre){
  let idGroup = valeur.getAttribute("idGroup");
  ajaxRequest("POST", "./server/get_nb_member.php", { 'idGroup': idGroup }, (data) => {
    if(data)
    {
      spanMembre.textContent = data.nbMember;
    }
  });
}
function renderDemande(spanDemande){
  let idGroup = valeur.getAttribute("idGroup");
  ajaxRequest("POST", "./server/get_nb_demande.php", { 'idGroup': idGroup }, (data) => {
    if(data)
    {
      console.log(data);
      if(data.nbMember == 0)
      {
        spanDemande.textContent = "0";
      }
      else{
        spanDemande.textContent = data.nbMember;
      }
      
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
  console.log(user.idUser);
  if(Mamow == idAdmin){
    message = `<button style="width: 80%;margin-bottom: 10px;margin-top: 0px;"class="refuser bouton-accepter" id="exclure-btn" idUser="${user.idUser}" action="reject">Exclure</button>`
  }
  return `<div id="bonhomme" class="gallery" userKey="${user.userKey}">
  <a target="_blank" href="${user.profilePic}">
    <img src="${user.profilePic}" width="600" height="400">
  </a>
  <div style="font-size:18px;" class="desc">${user.firstName} ${user.lastName}</div>
  ${message}
</div>`;
}
function getProfilMember() {
  const idGroup1 = valeur.getAttribute("idGroup");
  ajaxRequest("POST", "./server/get_Members_group.php", { 'idGroup': idGroup1 }, (data) => {
    renderProfilePics(data);
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
      renderDemands(data);
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
    <img src="${user.profilePic}" width="600" height="400">
  </a>
  <div class="desc" style="font-size:18px;">${user.firstName} ${user.lastName}</div>
  <button class="bouton-accepter" id="accept-btn" idUser="${user.idUser}" action="accept">Accepter</button>
  <button style="margin-bottom:10px;" class="refuser bouton-accepter" id="reject-btn" idUser="${user.idUser}" action="reject">Refuser</button>
</div>
`;
}