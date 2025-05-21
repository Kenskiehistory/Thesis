document.addEventListener('DOMContentLoaded', function() {
    const updateButton = document.getElementById('updateButton');
    const paymentsTable = document.getElementById('paymentsTable');

    updateButton.addEventListener('click', updatePaymentsTable);

    function updatePaymentsTable() {
        // Show loading indicator
        paymentsTable.innerHTML = '<tr><td colspan="8">Loading...</td></tr>';

        fetch('payment1.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: 'action=update_payments'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateTableContent(data.payments, data.creditResults);
            } else {
                paymentsTable.innerHTML = '<tr><td colspan="8">Error: ' + data.message + '</td></tr>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            paymentsTable.innerHTML = '<tr><td colspan="8">An error occurred while updating the table.</td></tr>';
        });
    }

    function updateTableContent(payments, creditResults) {
        let tableHTML = `
            <thead>
                <tr>
                    <th>Payment ID</th>
                    <th>Amount (PHP)</th>
                    <th>Status</th>
                    <th>Description</th>
                    <th>Created At</th>
                    <th>Email</th>
                    <th>Payer Name</th>
                    <th>Ledger Status</th>
                </tr>
            </thead>
            <tbody>
        `;

        if (payments.length > 0) {
            payments.forEach(payment => {
                const billing = payment.attributes.billing;
                const payerEmail = billing.email || 'N/A';
                const payerName = billing.name || 'N/A';
                const ledgerStatus = creditResults[payment.id];

                tableHTML += `
                    <tr>
                        <td>${payment.id}</td>
                        <td>${(payment.attributes.amount / 100).toFixed(2)}</td>
                        <td>${payment.attributes.status.charAt(0).toUpperCase() + payment.attributes.status.slice(1)}</td>
                        <td>${payment.attributes.description}</td>
                        <td>${new Date(payment.attributes.created_at * 1000).toLocaleString()}</td>
                        <td>${payerEmail}</td>
                        <td>${payerName}</td>
                        <td>${getLedgerStatusHTML(ledgerStatus)}</td>
                    </tr>
                `;
            });
        } else {
            tableHTML += '<tr><td colspan="8">No payments found</td></tr>';
        }

        tableHTML += '</tbody>';
        paymentsTable.innerHTML = tableHTML;
    }

    function getLedgerStatusHTML(ledgerStatus) {
        switch (ledgerStatus.status) {
            case 'processed':
                return '<span class="text-success">Amount added to ledger</span>';
            case 'already_processed':
                return '<span class="text-info">Amount already added to ledger</span>';
            case 'not_paid':
                return '<span class="text-warning">Payment not yet paid</span>';
            default:
                return `<span class="text-danger">${ledgerStatus.message}</span>`;
        }
    }
});