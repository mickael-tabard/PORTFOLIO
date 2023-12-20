

document.addEventListener('DOMContentLoaded', function () {

    // Décalage placeholder formulaire

    const formInput = document.querySelectorAll('.form-input')
    console.log(formInput);

    formInput.forEach(formInput => {
        const spanInput = formInput.querySelector('span')
        const input = formInput.querySelector('input')
        const svgUsername = formInput.querySelector('svg')

        input.addEventListener('focus', () => {
            spanInput.classList.add('active')
            svgUsername.classList.add('active')

        })
        input.addEventListener('blur', () => {
            
            if (input.value === '') {
                spanInput.classList.remove('active');
                svgUsername.classList.remove('active')
            }
        });
    });
});