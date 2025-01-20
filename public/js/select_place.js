const hall_p = document.querySelector(".screen > p:nth-child(1)");
const type_p = document.querySelector(".screen > p:nth-child(2)");
const seats_div = document.querySelector("div.seats");
const standard_tickets_count_span = document.querySelector("div.summary:nth-child(4) > span:nth-child(2)");
const premium_tickets_count_span = document.querySelector("div.summary:nth-child(5) > span:nth-child(2)");
const summary_span = document.querySelector("div.summary:nth-child(7) > span:nth-child(2)");
const start_select = document.querySelector("select#start");

function input_change(row, number, seat_name, event) {
    console.log(event.target.checked, event.target.id, row, number, seat_name);

    switch (seat_name) {
        case "standard":
            standard_tickets_count_span.innerText = parseInt(standard_tickets_count_span.innerText) + (event.target.checked ? 1 : -1);
            break;
        case "premium":
            premium_tickets_count_span.innerText = parseInt(premium_tickets_count_span.innerText) + (event.target.checked ? 1 : -1);
            break;
        default:
            break;
    }
}

function update_room(screening) {
    // Clear existing content
    seats_div.innerHTML = "";

    // Create table elements
    const table = document.createElement("table");
    const tbody = document.createElement("tbody");

    let currentRow;
    let currentRowHeader;

    // Generate table rows and cells
    screening.seats.forEach((seat) => {
        if (!currentRow || seat.row !== currentRowHeader) {
            // If a new row starts, create a new row and add a header
            currentRow = document.createElement("tr");
            currentRowHeader = seat.row;

            const th = document.createElement("th");
            th.textContent = seat.row.toUpperCase();
            currentRow.appendChild(th);
            tbody.appendChild(currentRow);
        }

        // Create a cell for the seat
        const td = document.createElement("td");
        const input = document.createElement("input");

        // Set input attributes
        input.id = `${seat.ID_Seat}`;
        input.className = `seat_${seat.seat_name}`;
        input.type = "checkbox";
        input.name = `seat`;
        input.disabled = seat.is_taken;
        input.checked = seat.is_taken;

        // Attach the change event programmatically
        input.addEventListener("change", (event) => {
            input_change(seat.row, seat.number, seat.seat_name, event);
        });

        // Append the input to the cell and the cell to the row
        td.appendChild(input);
        currentRow.appendChild(td);
    });

    // Append the body to the table and the table to the container
    table.appendChild(tbody);
    seats_div.appendChild(table);

    // Update hall and screening info
    hall_p.innerText = `Sala ${screening.ID_Hall}`;
    type_p.innerHTML = `${screening.screening_name}`;
}


start_select.addEventListener("change", (event) => {

    // let data = await (await fetch(`/api/places/${event.target.value}`, {
    //     method: 'POST'
    // })).json();
    let screening = data.find((screening) => screening.ID_Screening === parseInt(event.target.value));
    update_room(screening);
    //here we have to update hall seats, hall name, and price
});

document.querySelector("form").addEventListener("submit", async (event) => {
    event.preventDefault();
    event.stopPropagation();

    let selected_seats = Array.from(document.querySelectorAll("input[type=checkbox]:checked"))

    let one_seat_gaps = false

    selected_seats.forEach(seat => {
        let td = seat.parentElement
        let tdl1 = td.previousElementSibling
        let tdl2 = tdl1?.previousElementSibling

        if (tdl2 && tdl2?.firstElementChild?.checked && !tdl1?.firstElementChild?.checked) {
            one_seat_gaps = true
            return
        }

        let tdr1 = td.nextElementSibling;
        let tdr2 = tdr1?.nextElementSibling;
        if (tdr2 && tdr2?.firstElementChild?.checked && !tdr1?.firstElementChild?.checked) {
            one_seat_gaps = true
            return
        }
    });
    if (one_seat_gaps) {
        alert("Nie można zostawić 1 wolnego miejsca między rezerwacjami");
        return
    }
})


update_room(data[0]);

