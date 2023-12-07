// Global variables
const popup = document.getElementById('project-popup');
const closeButton = document.getElementById('close-popup');
const triggerButton = document.getElementById('popup-trigger');
const navigation = document.getElementById('navigation-fixed');
const headerImg = document.getElementById('header-img');

// Event listener for the trigger button to show the popup
triggerButton.addEventListener('click', (e) => {
    e.preventDefault();
    popup.style.display = 'block';
    navigation.style.position = 'static';
    headerImg.style.marginTop = '0';
});

// Event listener for the close button to hide the popup anc continue in the website
closeButton.addEventListener('click', (e) => {
    e.preventDefault();
    popup.style.display = 'none';
    navigation.style.position = 'fixed';
    headerImg.style.marginTop = '100px';
});