import { ajaxRequest, getCookie, viderContainer } from "./functions.js";

const userKey = getCookie('userKey');

document.addEventListener('DOMContentLoaded', () => {
  getAllItem();


});
function getAllItem() {
  ajaxRequest("POST", "./server/get_items_market.php", {"userKey" : userKey}, (data) => {
    if(data)
    {
      console.log(data);
      renderItems(data);
    }
    
  });
}
function renderItems(items) {
  items.forEach(u => {
    $('.container-users').append(renderItem(u))
  });
}
function renderItem(item) {
  return `<div class="profil">
    <div style="background-image:url(${item.ImageItem})" class="photo-profil">
    </div>
    <div style="margin: 10px;">${item.prix}</div>
    <div class="nom">${item.titre}</div>
  </div>`
}