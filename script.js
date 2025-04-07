
document.getElementById('phone').addEventListener('input', function () {
    this.value = this.value.replace(/[^0-9]/g, ''); // Remove non-numeric characters
});

// Initially hide all user-type-specific fields
document.querySelectorAll('.user-type-specific').forEach(field => {
    field.style.display = 'none';
});

function toggleUserSpecificFields(userType) {
    // Hide all specific fields and remove 'required' attributes
    document.querySelectorAll('.user-type-specific').forEach(field => {
        field.style.display = 'none';
        field.querySelectorAll('input').forEach(input => input.required = false);
    });

    // Show the selected user type fields and add 'required' attributes
    if (userType) {
        const specificFields = document.getElementById(`${userType}Fields`);
        if (specificFields) {
            specificFields.style.display = 'block';
            specificFields.querySelectorAll('input').forEach(input => input.required = true);
        }
    }
}

function validateMinimumValue(input) {
    if (input.value && input.value < 1) {
        input.value = ''; // Reset value if less than 1
    }
}

// Add event listeners for validation
['donationAmount', 'total', 'st'].forEach(id => {
    const element = document.getElementById(id);
    if (element) {
        element.addEventListener('input', function () {
            validateMinimumValue(this);
        });
    }
});

const signUpButton=document.getElementById('signUpButton');
const signInButton=document.getElementById('signInButton');
const signInForm=document.getElementById('signIn');
const signUpForm=document.getElementById('signup');

signUpButton.addEventListener('click',function(){
    signInForm.style.display="none";
    signUpForm.style.display="block";
})
signInButton.addEventListener('click', function(){
    signInForm.style.display="block";
    signUpForm.style.display="none";
})
