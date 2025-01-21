const hall_p = document.querySelector(".screen > p:nth-child(1)");
const type_p = document.querySelector(".screen > p:nth-child(2)");
const seats_div = document.querySelector("div.seats");
const start_select = document.querySelector("select#start");
const standard_tickets_count_span = document.querySelector("span#seat_std");
const premium_tickets_count_span = document.querySelector("span#seat_pro");
const bed_tickets_count_span = document.querySelector("span#seat_bed");
const discount_input = document.querySelector("input#discount_code");
const summary_span = document.querySelector("span#sum");
const discount_span = document.querySelector("span#disc");
const discounted_span = document.querySelector("span#total");
const VAT = 23;

function input_change(screening, seat, event) {
    console.log(event.target.checked, event.target.id, seat);

    let summary = parseFloat(summary_span.innerText)
    let checked = event.target.checked
    let value = parseFloat(seat.price) + parseFloat(screening.price);
    value = (1 + VAT / 100) * value;
    // debugger
    summary = summary + value * (checked ? 1 : -1)
    // debugger

    summary_span.innerText = summary.toFixed(2);
    switch (seat.seat_name) {
        case "standard":
            standard_tickets_count_span.innerText = parseInt(standard_tickets_count_span.innerText) + (checked ? 1 : -1);
            break;
        case "premium":
            premium_tickets_count_span.innerText = parseInt(premium_tickets_count_span.innerText) + (checked ? 1 : -1);
            break;
        case "bed":
            bed_tickets_count_span.innerText = parseInt(bed_tickets_count_span.innerText) + (checked ? 1 : -1);
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
        input.name = `ID_Seat[]`;
        input.value = seat.ID_Seat;
        input.disabled = seat.is_taken;
        input.checked = seat.is_taken;

        // Attach the change event programmatically
        input.addEventListener("change", (event) => {
            input_change(screening, seat, event);
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
    let screening = data.find((screening) => screening.ID_Screening === parseInt(event.target.value));
    update_room(screening);
    summary_span.innerText = '0.00';
    discount_span.innerText = '0.00';
    standard_tickets_count_span.innerText = '0';
    premium_tickets_count_span.innerText = '0';
    bed_tickets_count_span.innerText = '0';
    discount_input.value = '';

});

discount_input.addEventListener("keydown", async (event) => {
    if (event.key === "Enter") {
        event.preventDefault()
        let amount = 0
        const response = await fetch('/getDiscount', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
                'discount_code': event.target.value
            })
        });

        if (response.ok) {
            amount = parseFloat(await response.text());
        } else {
            console.error('Failed to fetch discount');
        }
        amount = (1 + VAT / 100) * amount;
        discount_span.innerHTML = amount.toFixed(2);
    }
    // event.stopPropagation()
})

const observer = new MutationObserver(() => {
    let summary = parseFloat(summary_span.innerText);
    let discount = parseFloat(discount_span.innerText);
    let discounted = Math.max(summary - discount, 0);
    discounted_span.innerText = discounted.toFixed(2);
});

observer.observe(summary_span, {childList: true, subtree: true});
observer.observe(discount_span, {childList: true, subtree: true});


document.querySelector("form").addEventListener("submit", async (event) => {
    event.preventDefault();
    event.stopPropagation();
    let selected_seats = Array.from(document.querySelectorAll("input[type=checkbox]:checked:not(:disabled)"));
    if (selected_seats.length === 0) {
        alert("Nie wybrano miejsc");
        return
    }

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
    console.log(event)
    //print form data to console
    console.log(new FormData(event.target));
    if (one_seat_gaps) {
        alert("Nie można zostawić 1 wolnego miejsca między rezerwacjami");
        return
    }

    //send fetch post with form data
    let response = await fetch('/addReservation', {
        method: 'POST',
        body: new FormData(event.target)
    });
    // Check for a redirect (3xx status)
    if (response.redirected) {
        // Reload the page to the new URL

    } else {
        // Continue with the normal result if no redirect
        let result = await response.json();
        console.log(response); // Handle the result if no redirect occurred
        console.log(result); // Handle the result if no redirect occurred
        let msg = `Pomyślne rezerwacje dla siedzeń ${JSON.stringify(result.successes)}`;
        if (result.fails.length > 0) {
            msg += `\nNieudane rezerwacje dla siedzeń ${JSON.stringify(result.fails)}}`;
        }
        alert(msg);
        data = result.new_data.data
        update_room(data.find((screening) => screening.ID_Screening === parseInt(start_select.value)));
        summary_span.innerText = '0.00';
        discount_span.innerText = '0.00';
        standard_tickets_count_span.innerText = '0';
        premium_tickets_count_span.innerText = '0';
        bed_tickets_count_span.innerText = '0';
        discount_input.value = '';
    }


})

update_room(data[0]);

