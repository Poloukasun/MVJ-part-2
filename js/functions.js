export function log(message) {
  console.log(message);
}

export function defilerBas () {
  window.scrollTo({
    top: document.documentElement.scrollHeight,
    behavior: 'smooth'
  });
}

export function getCookie(cookieName) {
  const cookiesStringArray = document.cookie.split(";");
  const cookies = [];

  for (let i = 0; i < cookiesStringArray.length; ++i) {
    const cookieParts = cookiesStringArray[i].trim().split("=");
    cookies[cookieParts[0]] = cookieParts[1];
  }

  if (cookies[cookieName]) return cookies[cookieName];

  return null;
}

export function viderContainer(selector) { // classe, id ou nom de la balise
  $(selector).empty();
}

export function ajaxRequest(type, url, data, func = null) {
  const dataType = "JSON";
  $.ajax({
    type: type,
    url: url,
    data: data,
    async:false,
    dataType: dataType,
    success: (response = null) => {
      if (typeof func === "function") func(response); // recoit en parametre la reponse du serveur
    },
  });
}

export function ajaxRequestFiles(data,url,type,func=null) {
  $.ajax({
    url: url,
    type: type,
    data: data,
    contentType: false,
    processData: false,
    success: (response = null) => {
      if(typeof func === 'function') func(response);
    }
  });
}

export const redirectTo = page => (window.location.href = `./${page}.php`);


export function setSelected(element) {
  $(".selected").removeClass("selected");
  $(element).addClass('selected');
}


export function partialRefresh(doRequest, func=null, time=null, interval=null) {
  if(doRequest) {
    interval = setInterval(() => {
      if(typeof func === 'function') {
        func();
      }
    }, time);
  } else {
    clearInterval(interval);
  }
  return interval;
}



 
