const searchBar = document.querySelector(".search input"),
searchIcon = document.querySelector(".search button"),
usersList = document.querySelector(".users-list");

searchIcon.onclick = ()=>{
  searchBar.classList.toggle("show");
  searchIcon.classList.toggle("active");
  searchBar.focus();
  if(searchBar.classList.contains("active")){
    searchBar.value = "";
    searchBar.classList.remove("active");
  }
}

searchBar.onkeyup = ()=>{
  let searchTerm = searchBar.value;
  if(searchTerm != ""){
    searchBar.classList.add("active");
  }else{
    searchBar.classList.remove("active");
  }
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "https://intranet.hogarymoda.com.co/Library/search.php", true);
  xhr.onload = ()=>{
    if(xhr.readyState === XMLHttpRequest.DONE){
        if(xhr.status === 200){
          let data = xhr.response;
          usersList.innerHTML = data;
        }
    }
  }
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.send("searchTerm=" + searchTerm);
}

setInterval(() =>{
  let xhr = new XMLHttpRequest();
  xhr.open("GET", "https://intranet.hogarymoda.com.co/Library/users.php", true);
  xhr.onload = ()=>{
    if(xhr.readyState === XMLHttpRequest.DONE){
        if(xhr.status === 200){
          let data = xhr.response;
          if(!searchBar.classList.contains("active")){
            usersList.innerHTML = data;
          }
        }
    }
  }
  xhr.send();
}, 500);


jQuery('#slcstatus').on('change', () => {

    switch (jQuery('#slcstatus').val()) {
      case 'Disponible':
        jQuery('#circle-status').attr('class', '');
        jQuery('#circle-status').addClass('status-dot-disponible');
        break;
      case 'Ocupado':
        jQuery('#circle-status').attr('class', '');
        jQuery('#circle-status').addClass('status-dot-ocupado');
        break;
      case 'No Molestar':
        jQuery('#circle-status').attr('class', '');
        jQuery('#circle-status').addClass('status-dot-nomolestar');
        break;

      default:
        jQuery('#circle-status').attr('class', '');
        jQuery('#circle-status').addClass('status-dot-offline');
        break;
    }

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "https://intranet.hogarymoda.com.co/Library/changestatususer.php", true);
    xhr.onload = ()=>{
      if(xhr.readyState === XMLHttpRequest.DONE){
          if(xhr.status === 200){
            //console.log('cambio correto');
          }
      }
    }
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("status=" + encodeURIComponent(jQuery('#slcstatus').val()));
    
});

document.getElementById('uploadForm').addEventListener('submit', function(event) {
  event.preventDefault();
  var formData = new FormData(this);
  var xhr = new XMLHttpRequest();
  xhr.open('POST', 'https://intranet.hogarymoda.com.co/Library/uploadImgProfile.php', true);
  xhr.onload = function () {
      if (xhr.status === 200) {
          //document.getElementById('uploadStatus').innerHTML = '<p class="text-success">Imagen subida correctamente.</p>';
          location.reload();
      } else {
          document.getElementById('uploadStatus').innerHTML = '<p class="text-danger">Error al subir la imagen.</p>';
      }
  };
  xhr.send(formData);
});




