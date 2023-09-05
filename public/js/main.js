const toggleMenu = document.querySelector('.toggle-menu');
const menu = document.querySelector('.menu');

const toggleSearch = document.querySelector('.toggle-search');
const search = document.querySelector('.search');

const searchNav = document.querySelector('.search-nav');
const togglePosts = document.querySelector('.toggle-posts');
const toggleUsers = document.querySelector('.toggle-users');
const postsResults = document.querySelector('.posts-results');
const usersResults = document.querySelector('.users-results');

const flash = document.querySelector('.flash');
const closeFlash = document.querySelector('.close');

window.onload = () => {
  menu.style.display = "none";
  search.style.display = "none";

  if(searchNav) {
    usersResults.style.display = "none";
    togglePosts.style.textDecoration = "underline";
  }
}

toggleMenu.addEventListener('click', () => {
  if(menu.style.display == "none") {
    menu.style.display = "block";
  } else {
    menu.style.display = "none";
  }
});

toggleSearch.addEventListener('click', () => {
  let move = document.querySelector('.header').nextElementSibling;

  if(search.style.display == "none") {
    search.style.display = "block";
    move.style.marginTop = "100px";
  } else {
    search.style.display = "none";
    move.style.marginTop = "25px";
  }
});

if(searchNav) {
  toggleUsers.addEventListener('click', () => {
    usersResults.style.display = "block";
    postsResults.style.display = "none";
    togglePosts.style.textDecoration = "none";
    toggleUsers.style.textDecoration = "underline";
  });
  
  togglePosts.addEventListener('click', () => {
    usersResults.style.display = "none";
    postsResults.style.display = "block";
    togglePosts.style.textDecoration = "underline";
    toggleUsers.style.textDecoration = "none";
  });
}

if(flash) {
  closeFlash.addEventListener('click', () => {
    flash.style.display = 'none';
  })
}