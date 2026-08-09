<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-display text-xl font-semibold text-ink leading-tight">
                    {{ __('QR Scanner') }}
                </h2>
                <p class="font-body text-sm text-slate">Scan customer pickup and return QR codes without leaving the camera view.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto flex max-w-7xl flex-col gap-6 sm:px-6 lg:px-8 lg:grid lg:grid-cols-[minmax(0,1.2fr)_minmax(320px,0.8fr)]">
            <section class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="border-b border-gray-100 px-6 py-5">
                    <h3 class="font-display text-base font-semibold text-ink">Camera Scanner</h3>
                    <p class="mt-1 font-body text-sm text-slate">Point the device camera at the customer's QR code. The scanner stays active for repeated scans.</p>
                </div>
                <div class="p-6">
                    <div class="rounded-3xl border border-dashed border-cyan-200 bg-cyan-50/70 p-4">
                        <div id="qr-reader" class="mx-auto max-w-2xl overflow-hidden rounded-2xl bg-white"></div>
                    </div>

                    <div class="mt-4 flex flex-wrap items-center gap-3">
                        <span id="scanner-status-badge" class="inline-flex rounded-full bg-cyan-100 px-3 py-1 font-body text-xs font-semibold uppercase tracking-wider text-cyan-800">
                            Scanning...
                        </span>
                        <p id="scanner-status-text" class="font-body text-sm text-slate">
                            Waiting for a QR code.
                        </p>
                    </div>
                </div>
            </section>

            <aside class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="border-b border-gray-100 px-6 py-5">
                    <h3 class="font-display text-base font-semibold text-ink">Scan Result</h3>
                    <p class="mt-1 font-body text-sm text-slate">Rental details and the next valid action will appear here after each scan.</p>
                </div>

                <div class="p-6">
                    <div id="result-empty" class="rounded-2xl border border-dashed border-gray-300 px-5 py-10 text-center">
                        <p class="font-display text-sm font-semibold text-ink">No scan result yet.</p>
                        <p class="mt-2 font-body text-sm text-gray-500">Scan a customer QR code to load rental details.</p>
                    </div>

                    <div id="result-panel" class="hidden space-y-4">
                        <div id="result-message" class="hidden rounded-md border px-4 py-3 text-sm"></div>

                        <div class="rounded-2xl border border-gray-200 bg-cloud p-5">
                            <dl class="space-y-3 font-body text-sm text-gray-700">
                                <div class="flex items-start justify-between gap-4">
                                    <dt class="font-medium text-slate-500">Rental ID</dt>
                                    <dd id="result-rental-id" class="text-right font-mono font-semibold text-ink"></dd>
                                </div>
                                <div class="flex items-start justify-between gap-4">
                                    <dt class="font-medium text-slate-500">Rental Item</dt>
                                    <dd id="result-gadget-name" class="text-right font-display font-semibold text-ink"></dd>
                                </div>
                                <div class="flex items-start justify-between gap-4">
                                    <dt class="font-medium text-slate-500">Customer</dt>
                                    <dd id="result-customer-name" class="text-right font-semibold text-ink"></dd>
                                </div>
                                <div class="flex items-start justify-between gap-4">
                                    <dt class="font-medium text-slate-500">Pickup Type</dt>
                                    <dd id="result-pickup-type" class="text-right"></dd>
                                </div>
                                <div class="flex items-start justify-between gap-4">
                                    <dt class="font-medium text-slate-500">Current Status</dt>
                                    <dd>
                                        <span id="result-status-badge" class="inline-flex rounded-full px-2.5 py-0.5 font-body text-xs font-semibold"></span>
                                    </dd>
                                </div>
                                <div class="flex items-start justify-between gap-4">
                                    <dt class="font-medium text-slate-500">Handed Over</dt>
                                    <dd id="result-handed-over-at" class="text-right"></dd>
                                </div>
                                <div class="flex items-start justify-between gap-4">
                                    <dt class="font-medium text-slate-500">Returned At</dt>
                                    <dd id="result-returned-at" class="text-right"></dd>
                                </div>
                            </dl>
                        </div>

                        <div id="action-panel" class="rounded-2xl border border-cyan-200 bg-cyan-50 p-5">
                            <p id="action-text" class="font-body text-sm text-cyan-900"></p>

                            <div class="mt-4 flex flex-wrap gap-3">
                                <button id="confirm-handover-button" type="button" class="hidden rounded-md bg-indigo px-4 py-2 text-sm font-body font-semibold text-white transition hover:bg-indigo-500">
                                    Confirm Handover
                                </button>

                                <button id="scan-again-button" type="button" class="rounded-md border border-gray-300 px-4 py-2 text-sm font-body font-semibold text-gray-700 transition hover:bg-gray-50">
                                    Clear Result
                                </button>
                            </div>
                        </div>

                        <div id="return-form-panel" class="hidden rounded-2xl border border-indigo-200 bg-indigo-50 p-5">
                            <form id="return-form" x-data="{ depositDecision: 'full_refund' }" class="space-y-4">
                                <input type="hidden" name="_method" value="PATCH">

                                <div>
                                    <p id="return-panel-title" class="font-body text-xs font-semibold uppercase tracking-wider text-indigo-800">Inline Return</p>
                                    <p id="return-panel-subtitle" class="mt-1 font-body text-sm text-indigo-900">Complete the return workflow directly from the scanner result panel.</p>
                                </div>

                                <div class="grid gap-4 sm:grid-cols-2">
                                    <div>
                                        <label for="scan-condition-on-return" class="mb-2 block font-body text-xs font-semibold uppercase tracking-wider text-indigo-800">
                                            Condition on Return
                                        </label>
                                        <select id="scan-condition-on-return" name="condition_on_return" class="w-full rounded-md border border-indigo-200 px-3 py-2 font-body text-sm text-gray-700 focus:border-indigo-400 focus:ring-indigo-400" required>
                                            <option value="good">Good</option>
                                            <option value="damaged">Damaged</option>
                                            <option value="missing_parts">Missing Parts</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label for="scan-deposit-decision" class="mb-2 block font-body text-xs font-semibold uppercase tracking-wider text-indigo-800">
                                            Deposit Decision
                                        </label>
                                        <select id="scan-deposit-decision" name="deposit_decision" x-model="depositDecision" class="w-full rounded-md border border-indigo-200 px-3 py-2 font-body text-sm text-gray-700 focus:border-indigo-400 focus:ring-indigo-400" required>
                                            <option value="full_refund">Full Refund</option>
                                            <option value="partial_refund">Partial Refund</option>
                                            <option value="deduct_all">Deduct All</option>
                                        </select>
                                    </div>
                                </div>

                                <div>
                                    <label for="scan-return-notes" class="mb-2 block font-body text-xs font-semibold uppercase tracking-wider text-indigo-800">
                                        Return Notes
                                    </label>
                                    <textarea id="scan-return-notes" name="return_notes" rows="3" class="w-full rounded-md border border-indigo-200 px-3 py-2 font-body text-sm text-gray-700 focus:border-indigo-400 focus:ring-indigo-400" placeholder="Optional notes about the returned item."></textarea>
                                </div>

                                <div x-show="depositDecision === 'partial_refund'">
                                    <label for="scan-deposit-refund-amount" class="mb-2 block font-body text-xs font-semibold uppercase tracking-wider text-indigo-800">
                                        Deposit Refund Amount
                                    </label>
                                    <input id="scan-deposit-refund-amount" type="number" name="deposit_refund_amount" min="0" step="0.01" class="w-full rounded-md border border-indigo-200 px-3 py-2 font-mono text-sm text-gray-700 focus:border-indigo-400 focus:ring-indigo-400">
                                </div>

                                <div x-show="depositDecision === 'partial_refund' || depositDecision === 'deduct_all'">
                                    <label for="scan-deposit-deduction-reason" class="mb-2 block font-body text-xs font-semibold uppercase tracking-wider text-indigo-800">
                                        Deduction Reason
                                    </label>
                                    <textarea id="scan-deposit-deduction-reason" name="deposit_deduction_reason" rows="3" class="w-full rounded-md border border-indigo-200 px-3 py-2 font-body text-sm text-gray-700 focus:border-indigo-400 focus:ring-indigo-400" placeholder="Required when the deposit is not fully refunded."></textarea>
                                </div>

                                <div id="return-late-fee-box" class="hidden rounded-md border border-amber-200 bg-amber-50 px-4 py-3 font-body text-sm text-amber-800">
                                    <p id="return-late-fee-text"></p>
                                    <label class="mt-3 inline-flex items-center gap-2 font-body text-sm text-indigo-900">
                                        <input id="scan-waive-late-fee" type="checkbox" name="waive_late_fee" value="1" class="rounded border-indigo-300 text-indigo-600 focus:ring-indigo-500">
                                        <span>Waive late fee</span>
                                    </label>
                                </div>

                                <div class="flex flex-wrap gap-3">
                                    <button id="confirm-return-button" type="submit" class="rounded-md border border-indigo-300 px-4 py-2 font-body text-sm font-semibold text-indigo-700 transition hover:bg-indigo-100">
                                        Confirm Return
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const lookupUrl = @json(route('admin.scan.lookup'));
            const handoverUrlTemplate = @json(route('admin.scan.confirm-handover', ['rental' => '__RENTAL__']));
            const returnUrlTemplate = @json(route('admin.rentals.return', ['rental' => '__RENTAL__']));
            const csrfToken = @json(csrf_token());
            const qrTokenParam = 'token';

            const statusBadge = document.getElementById('scanner-status-badge');
            const statusText = document.getElementById('scanner-status-text');
            const resultEmpty = document.getElementById('result-empty');
            const resultPanel = document.getElementById('result-panel');
            const resultMessage = document.getElementById('result-message');
            const resultRentalId = document.getElementById('result-rental-id');
            const resultGadgetName = document.getElementById('result-gadget-name');
            const resultCustomerName = document.getElementById('result-customer-name');
            const resultPickupType = document.getElementById('result-pickup-type');
            const resultStatusBadge = document.getElementById('result-status-badge');
            const resultHandedOverAt = document.getElementById('result-handed-over-at');
            const resultReturnedAt = document.getElementById('result-returned-at');
            const actionText = document.getElementById('action-text');
            const confirmHandoverButton = document.getElementById('confirm-handover-button');
            const scanAgainButton = document.getElementById('scan-again-button');
            const returnFormPanel = document.getElementById('return-form-panel');
            const returnPanelTitle = document.getElementById('return-panel-title');
            const returnPanelSubtitle = document.getElementById('return-panel-subtitle');
            const returnForm = document.getElementById('return-form');
            const returnLateFeeBox = document.getElementById('return-late-fee-box');
            const returnLateFeeText = document.getElementById('return-late-fee-text');
            const depositRefundAmountInput = document.getElementById('scan-deposit-refund-amount');
            const waiveLateFeeInput = document.getElementById('scan-waive-late-fee');
            const confirmReturnButton = document.getElementById('confirm-return-button');

            let isProcessing = false;
            let activeRental = null;
            let resetTimer = null;

            function updateScannerState(label, text, tone = 'sky') {
                const badgeClasses = {
                    sky: 'bg-cyan-100 text-cyan-800',
                    amber: 'bg-amber-100 text-amber-800',
                    green: 'bg-green-100 text-green-800',
                    red: 'bg-red-100 text-red-800',
                };

                statusBadge.textContent = label;
                statusBadge.className = `inline-flex rounded-full px-3 py-1 font-body text-xs font-semibold uppercase tracking-wider ${badgeClasses[tone] ?? badgeClasses.sky}`;
                statusText.textContent = text;
            }

            function showMessage(message, tone = 'sky') {
                const toneClasses = {
                    sky: 'border-cyan-200 bg-cyan-50 text-cyan-800',
                    amber: 'border-amber-200 bg-amber-50 text-amber-800',
                    green: 'border-green-200 bg-green-50 text-green-800',
                    red: 'border-red-200 bg-red-50 text-red-800',
                };

                resultMessage.textContent = message;
                resultMessage.className = `rounded-md border px-4 py-3 text-sm ${toneClasses[tone] ?? toneClasses.sky}`;
                resultMessage.classList.remove('hidden');
            }

            function clearMessage() {
                resultMessage.classList.add('hidden');
                resultMessage.textContent = '';
            }

            function formatTimestamp(value) {
                if (!value) {
                    return '-';
                }

                return new Date(value).toLocaleString();
            }

            function statusClasses(status) {
                if (status === 'approved') {
                    return 'bg-green-100 text-green-800';
                }

                if (status === 'rejected') {
                    return 'bg-red-100 text-red-800';
                }

                if (status === 'cancelled_by_customer') {
                    return 'bg-rose-100 text-rose-800';
                }

                if (status === 'returned' || status === 'completed') {
                    return 'bg-blue-100 text-blue-800';
                }

                return 'bg-yellow-100 text-yellow-800';
            }

            function humanizeStatus(status) {
                if (status === 'cancelled_by_customer') {
                    return 'Cancelled by Customer';
                }

                if (status === 'completed') {
                    return 'Completed';
                }

                return status.replaceAll('_', ' ').replace(/\b\w/g, (char) => char.toUpperCase());
            }

            function humanizeDepositStatus(status) {
                return status.replaceAll('_', ' ').replace(/\b\w/g, (char) => char.toUpperCase());
            }

            function resetReturnForm() {
                returnForm.reset();
                depositRefundAmountInput.value = '';
                waiveLateFeeInput.checked = false;
                depositRefundAmountInput.removeAttribute('max');
                returnLateFeeBox.classList.add('hidden');
                returnLateFeeText.textContent = '';
            }

            function resetResultPanel() {
                activeRental = null;
                clearMessage();
                resetReturnForm();
                confirmHandoverButton.classList.add('hidden');
                returnFormPanel.classList.add('hidden');
                actionText.textContent = '';
                resultPanel.classList.add('hidden');
                resultEmpty.classList.remove('hidden');
            }

            function scheduleReset(delay = 2000) {
                window.clearTimeout(resetTimer);
                resetTimer = window.setTimeout(() => {
                    resetResultPanel();
                    updateScannerState('Scanning...', 'Waiting for a QR code.', 'sky');
                }, delay);
            }

            function configureReturnForm(data) {
                resetReturnForm();
                depositRefundAmountInput.value = data.deposit_amount ?? 0;
                depositRefundAmountInput.max = data.deposit_amount ?? 0;

                if ((data.days_overdue ?? 0) > 0 && (data.late_fee_amount ?? 0) > 0) {
                    returnLateFeeText.textContent = `Late fee: ${Number(data.late_fee_amount).toFixed(2)} for ${data.days_overdue} day${data.days_overdue === 1 ? '' : 's'} overdue`;
                    returnLateFeeBox.classList.remove('hidden');
                }
            }

            function renderResult(data) {
                activeRental = data;
                clearMessage();

                resultRentalId.textContent = `#${data.id}`;
                resultGadgetName.textContent = data.gadget_name ?? '-';
                resultCustomerName.textContent = data.customer_name ?? '-';
                resultPickupType.textContent = data.pickup_type === 'delivery' ? 'Delivery' : 'Walk-in';
                resultStatusBadge.textContent = humanizeStatus(data.status);
                resultStatusBadge.className = `inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold ${statusClasses(data.status)}`;
                resultHandedOverAt.textContent = formatTimestamp(data.handed_over_at);
                resultReturnedAt.textContent = formatTimestamp(data.returned_at);

                confirmHandoverButton.classList.add('hidden');
                returnFormPanel.classList.add('hidden');

                const isDelivery = data.pickup_type === 'delivery';

                if (data.next_action === 'handover') {
                    actionText.textContent = isDelivery
                        ? 'This delivery order is approved. Scan again once the courier confirms delivery to mark it as delivered.'
                        : 'This rental is approved and ready for physical handover.';
                    confirmHandoverButton.textContent = isDelivery ? 'Confirm Delivery' : 'Confirm Handover';
                    confirmHandoverButton.classList.remove('hidden');
                } else if (data.next_action === 'return') {
                    actionText.textContent = isDelivery
                        ? 'This order has been delivered. Once the returned package has arrived and been inspected, confirm the return below.'
                        : 'This rental has already been handed over. Complete the return workflow below.';
                    returnPanelTitle.textContent = isDelivery ? 'Return Received' : 'Inline Return';
                    returnPanelSubtitle.textContent = isDelivery
                        ? 'Confirm the returned package has arrived and been inspected, then complete the return workflow below.'
                        : 'Complete the return workflow directly from the scanner result panel.';
                    configureReturnForm(data);
                    returnFormPanel.classList.remove('hidden');
                } else if (data.next_action === 'already_completed') {
                    actionText.textContent = 'This rental has already been completed.';
                } else {
                    actionText.textContent = 'This rental is not yet approved for handover.';
                }

                resultEmpty.classList.add('hidden');
                resultPanel.classList.remove('hidden');
            }

            function extractQrToken(decodedText) {
                try {
                    const parsedUrl = new URL(decodedText);
                    return parsedUrl.searchParams.get(qrTokenParam);
                } catch (error) {
                    return null;
                }
            }

            async function lookupRental(qrToken) {
                updateScannerState('Processing...', 'Looking up rental details from the scanned QR code.', 'amber');

                const response = await fetch(lookupUrl, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ qr_token: qrToken }),
                });

                const data = await response.json().catch(() => ({}));

                if (!response.ok) {
                    throw new Error(data.message || 'Unable to find a rental for that QR code.');
                }

                return data;
            }

            async function handleScan(decodedText) {
                if (isProcessing) {
                    return;
                }

                const qrToken = extractQrToken(decodedText);

                if (!qrToken) {
                    updateScannerState('Invalid QR', 'The scanned QR code did not contain a valid rental token.', 'red');
                    showMessage('The scanned QR code is missing the expected rental token.', 'red');
                    scheduleReset();
                    return;
                }

                isProcessing = true;

                try {
                    const rental = await lookupRental(qrToken);
                    renderResult(rental);
                    updateScannerState('Ready', `Loaded rental #${rental.id}. Camera remains active for the next scan.`, 'green');
                } catch (error) {
                    resetResultPanel();
                    showMessage(error.message, 'red');
                    resultEmpty.classList.add('hidden');
                    resultPanel.classList.remove('hidden');
                    actionText.textContent = 'Try scanning another QR code.';
                    updateScannerState('Lookup Failed', error.message, 'red');
                } finally {
                    isProcessing = false;
                }
            }

            async function confirmHandover() {
                if (!activeRental) {
                    return;
                }

                confirmHandoverButton.disabled = true;
                confirmHandoverButton.textContent = 'Confirming...';
                updateScannerState('Processing...', `Confirming handover for rental #${activeRental.id}.`, 'amber');

                try {
                    const response = await fetch(handoverUrlTemplate.replace('__RENTAL__', activeRental.id), {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify({}),
                    });

                    const data = await response.json().catch(() => ({}));

                    if (!response.ok) {
                        throw new Error(data.message || 'Unable to confirm handover.');
                    }

                    activeRental.handed_over_at = data.handed_over_at;
                    activeRental.next_action = 'return';
                    renderResult(activeRental);
                    showMessage(data.message || 'Handover confirmed successfully.', 'green');
                    updateScannerState('Confirmed', `Handover confirmed for rental #${activeRental.id}. Returning to scan mode shortly.`, 'green');
                    scheduleReset();
                } catch (error) {
                    showMessage(error.message, 'red');
                    updateScannerState('Action Failed', error.message, 'red');
                } finally {
                    confirmHandoverButton.disabled = false;
                    confirmHandoverButton.textContent = activeRental && activeRental.pickup_type === 'delivery'
                        ? 'Confirm Delivery'
                        : 'Confirm Handover';
                }
            }

            async function submitReturn(event) {
                event.preventDefault();

                if (!activeRental) {
                    return;
                }

                confirmReturnButton.disabled = true;
                confirmReturnButton.textContent = 'Confirming...';
                updateScannerState('Processing...', `Confirming return for rental #${activeRental.id}.`, 'amber');

                try {
                    const formData = new FormData(returnForm);

                    const response = await fetch(returnUrlTemplate.replace('__RENTAL__', activeRental.id), {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: formData,
                    });

                    const data = await response.json().catch(() => ({}));

                    if (!response.ok) {
                        const validationMessage = data.errors
                            ? Object.values(data.errors).flat()[0]
                            : null;

                        throw new Error(validationMessage || data.message || 'Unable to confirm return.');
                    }

                    const lateFeeSummary = data.late_fee_waived
                        ? 'late fee waived'
                        : ((data.late_fee_amount ?? 0) > 0 ? `late fee applied (${Number(data.late_fee_amount).toFixed(2)})` : 'no late fee');
                    const successMessage = `Return confirmed - ${humanizeDepositStatus(data.deposit_status)} (${Number(data.deposit_refund_amount ?? 0).toFixed(2)}) and ${lateFeeSummary}.`;

                    activeRental.returned_at = data.returned_at;
                    activeRental.status = 'completed';
                    activeRental.next_action = 'already_completed';
                    renderResult(activeRental);
                    showMessage(successMessage, 'green');
                    updateScannerState('Confirmed', `Return confirmed for rental #${activeRental.id}. Returning to scan mode shortly.`, 'green');
                    scheduleReset();
                } catch (error) {
                    showMessage(error.message, 'red');
                    updateScannerState('Action Failed', error.message, 'red');
                } finally {
                    confirmReturnButton.disabled = false;
                    confirmReturnButton.textContent = 'Confirm Return';
                }
            }

            confirmHandoverButton.addEventListener('click', confirmHandover);
            returnForm.addEventListener('submit', submitReturn);
            scanAgainButton.addEventListener('click', () => {
                window.clearTimeout(resetTimer);
                resetResultPanel();
                updateScannerState('Scanning...', 'Waiting for a QR code.', 'sky');
            });

            const html5QrCode = new Html5Qrcode('qr-reader');

            html5QrCode
                .start(
                    { facingMode: 'environment' },
                    {
                        fps: 10,
                        qrbox: { width: 280, height: 280 },
                    },
                    handleScan
                )
                .then(() => {
                    updateScannerState('Scanning...', 'Camera ready. Scan a customer QR code.', 'sky');
                })
                .catch((error) => {
                    resetResultPanel();
                    showMessage(`Unable to start the camera scanner: ${error}`, 'red');
                    resultEmpty.classList.add('hidden');
                    resultPanel.classList.remove('hidden');
                    actionText.textContent = 'Allow camera access and refresh the page to try again.';
                    updateScannerState('Camera Error', 'Camera access failed.', 'red');
                });
        });
    </script>
</x-app-layout>
