

document.addEventListener('DOMContentLoaded', function () {

    // Toggle du formulaire d'inscription
    // const toggleRegisterBtn = document.querySelector('#create-account')
    // const containerFormRegister = document.querySelector('.container-form')

    // toggleRegisterBtn.addEventListener('click', () => {

    // })


    // Label Aggre Terms class change

    const aggreTerm = document.querySelector('label[for="registration_form_agreeTerms"]')
    const aggreTermSvg = document.querySelector('.agreeTerms label span svg')

    aggreTerm.addEventListener('click', () => {
        aggreTerm.classList.toggle('checked')
        aggreTermSvg.classList.toggle('hide')
    })




    // Décalage placeholder formulaire

    const formInput = document.querySelectorAll('.form-input')
    console.log(formInput);


    formInput.forEach(formInput => {
        const labelPictureFile = document.querySelector('.form-input label[for="registration_form_pictureFile_file"]')
        console.log(labelPictureFile);
        const spanInput = formInput.querySelector('span')
        const input = formInput.querySelector('input')
        const svgUsername = formInput.querySelector('svg')
        const paragraphe = formInput.querySelector('p')

        input.addEventListener('focus', () => {

            spanInput.classList.add('active')
            if (svgUsername) {
                svgUsername.classList.add('active')
            }
            if (paragraphe) {
                labelPictureFile.classList.add('active')
                paragraphe.classList.add('active')
                // paragraphe.textContent = labelPictureFile.value
                console.log(input.files);

            }

        })
        input.addEventListener('blur', () => {
            if (input.value === '') {
                spanInput.classList.remove('active');
                if (svgUsername) {
                    svgUsername.classList.remove('active')

                }
                if (paragraphe) {
                    labelPictureFile.classList.remove('active')
                    paragraphe.classList.remove('active')


                }
            }
        });
        input.addEventListener('change', () => {
            console.log(input.files);
            if (paragraphe) {
                labelPictureFile.classList.remove('active')

                paragraphe.textContent = input.files[0].name
            }
        })



    });


    // input.

});