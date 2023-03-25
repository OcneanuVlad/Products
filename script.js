let typeSelector = document.querySelector('#productType');
let selectedType = 'DVD';

typeSelector.addEventListener('change', (event) => {
    selectedType = event.target.value;

    let inputFields = document.querySelectorAll('.typeField');
    inputFields.forEach((field) => {
        field.style.display = 'none';
        let nodes = field.getElementsByTagName('INPUT');
        Array.from(nodes).forEach((node) => {
            node.required = false;
        })
    });

    let selectedField = document.querySelector(`.${selectedType}Field`);
    selectedField.style.display = 'block';
    let nodes = selectedField.getElementsByTagName('INPUT');
    Array.from(nodes).forEach((node) => {
        node.required = true;
    })
});
