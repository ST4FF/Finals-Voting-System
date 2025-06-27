document.querySelector('.profile-picture-form').addEventListener('mouseenter' , () =>{
    document.querySelector('.overlay').style.opacity = 1;
});

document.querySelector('.profile-picture-form').addEventListener('mouseleave', () =>{
   document.querySelector('.overlay').style.opacity = 0; 
});