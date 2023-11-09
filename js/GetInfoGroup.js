import { ajaxRequest, getCookie, viderContainer } from "./functions.js";

const valeur = document.querySelector('#idGroup');
const userKey = getCookie('userKey');

document.addEventListener('DOMContentLoaded', () => {
  getProfilMember();
  getDemandeGroup();

  let divGroup = document.getElementById('groupe');
  let divPub = document.getElementById('pub');
  let divDemande = document.getElementById('demande');
  let divAll = document.getElementById('all-Member');

  let boutonDemande = document.getElementById('btnDemande');

  boutonDemande.addEventListener("click", function(){
    divGroup.style.display = "none";
    divPub.style.display = "none";
    divAll.style.display = "none";
    divDemande.style.display = "block";
  });

  var boutonQuitter = document.querySelector("#Quitter");
  let idGroup = valeur.getAttribute("idGroup");
  boutonQuitter.addEventListener("click", function (e) {
    
    ajaxRequest("POST", "./server/delete_member_group.php", {"userKey" : userKey, "idGroup" : idGroup}, (data) => {
      if (data) {
        console.log(data);
      }
    })
  });
  var boutonAll = document.querySelector("#All-Members");
  boutonAll.addEventListener("click", function (e) {
    
    ajaxRequest("POST", "./server/get_all_member_group.php", {"userKey":userKey,"idGroup" : idGroup}, (data) => {
      divGroup.style.display = "none";
      divPub.style.display = "none";
      divDemande.style.display = "none";
      divAll.style.display = "block";
      if (data) {
        console.log(data);
        viderContainer('#all-Member');
        renderAlls(data);
      }
    })
  });
  
  if(boutonDemande.style.display !== "none"){
    let boutonAccept = document.getElementById('accept-btn');
    let boutonRefuse = document.getElementById('reject-btn');
    let DivDemand = document.getElementById('bonhomme');
    if(boutonRefuse !== null){
      const idUser = boutonRefuse.getAttribute('iduser');
    
    boutonAccept.addEventListener("click", function (e) {
      
      ajaxRequest("POST", "./server/accept_member_demand.php", {"idUser" : idUser, "idGroup" : idGroup}, (data) => {
        if (data) {
          console.log(data);
          DivDemand.remove();
        }
      })
    });
    boutonRefuse.addEventListener("click", function (e) {
      
      ajaxRequest("POST", "./server/reject_member_demand.php", {"idUser" : idUser, "idGroup" : idGroup}, (data) => {
        if (data) {
          console.log(data);
          DivDemand.remove();
        }
      })
    });
  }
  }
  

});
function renderAlls(users){
  users.forEach(u => {
    $('#all-Member').append(renderAll(u))
  });
}
function renderAll(user)
{
  return `<div class="gallery" userKey="${user.userKey}">
  <a target="_blank" href="${user.profilePic}">
    <img src="${user.profilePic}" width="600" height="400">
  </a>
  <div class="desc">${user.firstName} ${user.lastName}</div>
  <button style="width: 80%; margin-left: 10%;margin-bottom: 10px;margin-top: 0px;"class="refuser bouton-accepter" id="exclure-btn" idUser="${user.idUser}" action="reject">Exclure</button>
</div>`;
}
function getProfilMember(){
  const idGroup1 = valeur.getAttribute("idGroup");
  ajaxRequest("POST", "./server/get_Members_group.php", {'idGroup': idGroup1}, (data) => {
    renderProfilePics(data);
  });
}
function renderProfilePics(users){
  users.forEach(u => {
    $('.image-container').append(renderProfilePic(u))
  });
}
function renderProfilePic(user)
{
  return `<div class="profileImage" style="width: 50px;border-radius: 50%; margin-left:-10px;background-image:url(${user.profilePic}); height:50px;" title="${user.firstName} ${user.lastName}"><div>`
}
function getDemandeGroup(){
  const idGroup1 = valeur.getAttribute("idGroup");
  ajaxRequest("POST", "./server/get_demande_group.php", {'idGroup': idGroup1}, (data) => {
    if(data.length > 0)
    {
      renderDemands(data);
    }
    else{
      $('#demande').text('Aucune demande');
    }
  });
}
function renderDemands(users){
  users.forEach(u => {
    $('#demande').append(renderDemand(u))
  });
}
function renderDemand(user)
{
  return `<div class="profil" id="bonhomme" userkey="${user.userKey}">
  <div class="photo-profil">
  </div>
  <div class="nom">${user.firstName} ${user.lastName}</div>
  <div class="state">
    
      <button class="bouton-accepter" id="accept-btn" idUser="${user.idUser}" action="accept">Accepter</button>
      <button class="refuser bouton-accepter" id="reject-btn" idUser="${user.idUser}" action="reject">Refuser</button>
  
  </div>
</div>`;
}