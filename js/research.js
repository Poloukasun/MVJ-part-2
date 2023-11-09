// import { ajaxRequest, getCookie, viderContainer } from "./functions.js";
// import { getUsers, renderUsers } from "./getUsers.js";
// // import { getFriends, renderFriends } from "./getFriends.js";

// $(document).ready(function () {
//   const userKey = getCookie("userKey");

//   function updateResearch() {
//     const researchBar = document.querySelector("#search");
//     const btnSearch = document.querySelector(".search-btn");
//     let valResearched = "";
    
//     $(btnSearch).on("click", (e) => {

//       e.preventDefault();
//       viderContainer(".users-container");
//       valResearched = $(researchBar).val();
//       const selectedBtn = $(".selected");
//       let url;
//       let type = '*';

//       if (!valResearched) {
//         switch ($(selectedBtn).attr("id")) {
//           case "all-btn":
//             getUsers();
//             break;
//           case "friends":
//             renderFriends(getFriends());
//             break;
//           case "see-requests-btn":
//             console.log("REQUESTS");
//         }
//       } else {
//         viderContainer(".container-users");
//         if ($(selectedBtn).attr("id") === "all-btn") {
//           url = "./server/get_users_word_key.php";
//         } else if ($(selectedBtn).attr("id") === "friends") {
//           url = "./server/get_friends_key_word.php";
//           type = 'f';
//         }
        
//         ajaxRequest("GET", url, { keyWord:valResearched, userKey:userKey}, (res) => {
//           if (res.length > 0) {
//             if(type === '*')
//               renderUsers(res, userKey);
//             else if(type === 'f') 
//               renderFriends(res);  
//           }
//         });
//       }
//     });
//   }
//   updateResearch();
// });
