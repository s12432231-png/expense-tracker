```javascript
// Validate the expense form before submission.
```
document.addEventListener
("DOMContentLoaded", function () 
{
    const form = document.querySelector("form");

    if (!form) return;

    const description = form.querySelector('[name="description"]');
    const amount = form.querySelector('[name="amount"]');

    function showError(input, message) 
    {
        let error = input.parentElement.querySelector(".js-error");

        if (!error) 
        {
            error = document.createElement("small");
            error.className = "js-error text-danger d-block";
            input.parentElement.appendChild(error);
        }

        error.textContent = message;
    }

    function clearError(input) 
    {
        const error = input.parentElement.querySelector(".js-error");

        if (error) 
        {
            error.remove();
        }
    }

    form.addEventListener("submit", function (event) 
    {
        let valid = true;

        clearError(description);
        clearError(amount);

        if (description.value.trim() === "") 
    
        {
            showError(description, "Description is required.");
            valid = false;
        }

        if (amount.value.trim() === "" || Number(amount.value) <= 0) 
        {
            showError(amount, "Amount must be greater than zero.");
            valid = false;
        }

        if (!valid)
        {
            event.preventDefault();
        }
    }
);
}
);