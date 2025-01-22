const movies_table = document.querySelector("div#movies_list>form>table>tbody");
const reservations_table = document.querySelector("div#reservations_list>form>table>tbody");
const screenings_table = document.querySelector("div#screenings_list>form>table>tbody");
const clients_table = document.querySelector("div#clients_list>form>table>tbody");
const users_table = document.querySelector("div#users_list>form>table>tbody");


[
    {table: movies_table, url: "/removeMovie", key: "ID_Movie"},
    {table: reservations_table, url: "/removeReservation", key: "ID_Reservation"},
    {table: screenings_table, url: "/removeScreening", key: "ID_Screening"},
    {table: clients_table, url: "/removeClient", key: "ID_Client"},
    {table: users_table, url: "/removeUser", key: "ID_User"}
].forEach(({table, url, key}) => {
    table.querySelectorAll("span").forEach(span => {
        span.addEventListener('click', async () => {
            let id = parseInt(span.parentElement.parentElement.querySelector("td:first-child").innerText)
            //send post form with ID_Movie set to id
            let formData = new FormData();
            formData.append(key, id);
            let resp = await fetch(url, {
                method: 'post',
                body: formData
            })
            //let data = await resp.text()
            if (resp.status === 401) {
                alert("Error: Nie jesteś zalogowany")
            } else if (resp.status === 403) {
                alert("Error: Nie masz uprawnień do tej akcji")
            } else if (resp.status !== 200) {
                alert("Error: Nie ma takiego elementu, lub inne elementy od niego zależą")
            } else {
                span.parentElement.parentElement.remove()
            }


        })
    })
})


