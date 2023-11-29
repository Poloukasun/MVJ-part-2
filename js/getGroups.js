import { ajaxRequest, getCookie, partialRefresh, viderContainer } from "./functions.js";

const userKey = getCookie('userKey');


document.addEventListener('DOMContentLoaded', () => {
  getYourGroup();
  getGroup();
  rejoindreGroupe();

  partialRefresh(true, getGroup, 15000);
  partialRefresh(true, getYourGroup, 15000);
  $("#refresh-all").click(function(e) {
    getGroup();
    viderContainer("#renderYourGroup");
    getYourGroup();
    rejoindreGroupe();
    
    $(this).addClass("spin");
    setTimeout(() => {
      $(this).removeClass("spin");
    },1000);
  });
});
function rejoindreGroupe () {
  var boutons = document.querySelectorAll(".Bgroupe");

  boutons.forEach(function (bouton) {
    bouton.addEventListener("click", function (e) {
      //const parent = $(e.target).parent().parent();
      const isPrivate = bouton.getAttribute("isPrivate");
      const idGroup = bouton.getAttribute("groupKey");
      if (isPrivate == 1) {
        bouton.innerHTML = "Demande envoyé";
      }
      else {

        bouton.innerHTML = "Afficher le groupe";
        console.log($('.Bgroupe'));
        bouton.removeEventListener('click', this);

        bouton.addEventListener('click', function (e) {
          window.location = `./feed-group.php?${idGroup}`;
        });

      }
      const value = { "idGroup": idGroup, "isPrivate": isPrivate, "userKey": userKey };
      // console.log(value);
      // console.log(parent);
      ajaxRequest("POST", "./server/join_group.php", value, (data) => {
        if (data) {
          // console.log(data);
        }
      })
    });
  });
}
function getGroup() {
  ajaxRequest("POST", "./server/get_group.php", { 'userKey': userKey }, (data) => {
    viderContainer('#renderGroup');
    renderGroups(data);
    rejoindreGroupe();
  });
}

function getYourGroup() {
  ajaxRequest("POST", "./server/get_your_group.php", { 'userKey': userKey }, (data) => {
    viderContainer('#renderYourGroup');
    renderYourGroups(data)
  });
}
function renderYourGroup(groupe) {
  return `<a href="./feed-group.php?${groupe.idGroupe}" style="color:black"><p idAmin=${groupe.idAdmin} class="nav-groupe box-groupe">
  <img style="width: 50px; border-radius:15px; object-fit:cover;" src="${groupe.imageUrl}">
  <span class="nom-box">${groupe.name}</span>
  </p></a>`;
}
function renderYourGroups(groupes) {
  groupes.forEach(g => {
    $('#renderYourGroup').append(renderYourGroup(g));
  });
}
function renderGroup(groupe) {
  let text = groupe.isJoin == 0 ? "Demande envoyé" : "Rejoindre le groupe";
  let bouton = `<button id='messagePriver' groupKey='${groupe.idGroupe}' isPrivate=${groupe.isPrivate}  class="Bgroupe" type="button">${text}</button>`;
  if (groupe.isJoin == 0) {
    bouton = `<button disabled  id='messagePriver' groupKey='${groupe.idGroupe}' isPrivate=${groupe.isPrivate}  class="Bgroupe2" type="button">${text}</button>`;
  }
  return `<div class="gallery" style="border-radius: 10px;" idGroupe='${groupe.idGroupe}'>
  <a target="_blank" href="${groupe.imageUrl}">
  <div class="image-groupe" style="background-image:url(${groupe.imageUrl}); border-radius: 10px 10px 0 0;"></div>
  </a>
  <div class="titre-groupe">${groupe.name}</div>
  <div class="desc">
    ${bouton}
  </div>
</div>`;
}
function renderGroups(groupes) {
  groupes.forEach(g => {
    $('#renderGroup').append(renderGroup(g));
  });
}