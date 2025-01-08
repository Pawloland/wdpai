document.getElementById("start").addEventListener("change", async (event) => {

    let data = await (await fetch(`/api/places/${event.target.value}`, {
        method: 'POST'
    })).json();
    console.log(data);

    //here we have to update hall seats, hall name, and price
});

