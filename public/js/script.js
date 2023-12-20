document.addEventListener('DOMContentLoaded', function () {
    // Gérer l'opacity du boutton en fonction de la longueur du texte saisi par l'utilisateur.
    const tweetTextArea = document.querySelector('#tweet_content')
    const tweetBtnPost = document.querySelector('.main-button')
    tweetTextArea.addEventListener('input', () => {

        if (tweetTextArea.value.trim().length > 0) {
            tweetBtnPost.style.opacity = '1'
        } else {
            tweetBtnPost.style.opacity = '0.5';
        }
    })
    
    // Gestion de l'infobulle pour le logout.
    const userProfile = document.querySelector('#user-profile')
    const bubbleLogout = document.querySelector('.bubble-logout')

    userProfile.addEventListener('click', () => {
        bubbleLogout.classList.toggle('hide')

        if (!bubbleLogout.classList.contains('hide')) {
            userProfile.classList.remove('user-profile-active')

            const fullPage = document.createElement('div')
            fullPage.className ='full-page'

            document.body.appendChild(fullPage)

            fullPage.addEventListener('click', () => {
                document.body.removeChild(fullPage)
                bubbleLogout.classList.toggle('hide')
                userProfile.classList.add('user-profile-active')
            })
        } 

    })

 


    
});
