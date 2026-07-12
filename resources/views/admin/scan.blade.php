<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('QR Scanner') }}
                </h2>
                <p class="text-sm text-gray-600">Scan customer pickup and return QR codes without leaving the camera view.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto flex max-w-7xl flex-col gap-6 sm:px-6 lg:px-8 lg:grid lg:grid-cols-[minmax(0,1.2fr)_minmax(320px,0.8fr)]">
            <section class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="border-b border-gray-100 px-6 py-5">
                    <h3 class="text-base font-semibold text-gray-900">Camera Scanner</h3>
                    <p class="mt-1 text-sm text-gray-600">Point the device camera at the customer's QR code. The scanner stays active for repeated scans.</p>
                </div>
                <div class="p-6">
                    <div class="rounded-3xl border border-dashed border-sky-200 bg-sky-50/70 p-4">
                        <div id="qr-reader" class="mx-auto max-w-2xl overflow-hidden rounded-2xl bg-white"></div>
                    </div>

                    <div class="mt-4 flex flex-wrap items-center gap-3">
                        <span id="scanner-status-badge" class="inline-flex rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-sky-800">
                            Scanning...
                        </span>
                        <p id="scanner-status-text" class="text-sm text-gray-600">
                            Waiting for a QR code.
                        </p>
                    </div>
                </div>
            </section>

            <aside class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="border-b border-gray-100 px-6 py-5">
                    <h3 class="text-base font-semibold text-gray-900">Scan Result</h3>
                    <p class="mt-1 text-sm text-gray-600">Rental details and the next valid action will appear here after each scan.</p>
                </div>

                <div class="p-6">
                    <div id="result-empty" class="rounded-2xl border border-dashed border-gray-300 px-5 py-10 text-center">
                        <p class="text-sm font-semibold text-gray-900">No scan result yet.</p>
                        <p class="mt-2 text-sm text-gray-500">Scan a customer QR code to load rental details.</p>
                    </div>

                    <div id="result-panel" class="hidden space-y-4">
                        <div id="result-message" class="hidden rounded-md border px-4 py-3 text-sm"></div>

                        <div class="rounded-2xl border border-gray-200 bg-gray-50 p-5">
                            <dl class="space-y-3 text-sm text-gray-700">
                                <div class="flex items-start justify-between gap-4">
                                    <dt class="font-medium text-gray-500">Rental ID</dt>
                                    <dd id="result-rental-id" class="text-right font-semibold text-gray-900"></dd>
                                </div>
                                <div class="flex items-start justify-between gap-4">
                                    <dt class="font-medium text-gray-500">Gadget</dt>
                                    <dd id="result-gadget-name" class="text-right font-semibold text-gray-900"></dd>
                                </div>
                                <div class="flex items-start justify-between gap-4">
                                    <dt class="font-medium text-gray-500">Customer</dt>
                                    <dd id="result-customer-name" class="text-right font-semibold text-gray-900"></dd>
                                </div>
                                <div class="flex items-start justify-between gap-4">
                                    <dt class="font-medium text-gray-500">Pickup Type</dt>
                                    <dd id="result-pickup-type" class="text-right"></dd>
                                </div>
                                <div class="flex items-start justify-between gap-4">
                                    <dt class="font-medium text-gray-500">Current Status</dt>
                                    <dd>
                                        <span id="result-status-badge" class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold"></span>
                                    </dd>
                                </div>
                                <div class="flex items-start justify-between gap-4">
                                    <dt class="font-medium text-gray-500">Handed Over</dt>
                                    <dd id="result-handed-over-at" class="text-right"></dd>
                                </div>
                                <div class="flex items-start justify-between gap-4">
                                    <dt class="font-medium text-gray-500">Returned At</dt>
                                    <dd id="result-returned-at" class="text-right"></dd>
                                </div>
                            </dl>
                        </div>

                        <div id="action-panel" class="rounded-2xl border border-sky-200 bg-sky-50 p-5">
                            <p id="action-text" class="text-sm text-sky-900"></p>

                            <div class="mt-4 flex flex-wrap gap-3">
                                <button id="confirm-handover-button" type="button" class="hidden rounded-md bg-sky-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-sky-500">
                                    Confirm Handover
                                </button>

                                <a id="proceed-to-return-link" href="#" class="hidden rounded-md border border-blue-300 px-4 py-2 text-sm font-semibold text-blue-700 transition hover:bg-blue-100">
                                    Proceed to Return
                                </a>

                                <button id="scan-again-button" type="button" class="rounded-md border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                                    Clear Result
                                </button>
                            </div>
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
            const rentalsIndexUrl = @json(route('admin.rentals.index'));
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
            const proceedToReturnLink = document.getElementById('proceed-to-return-link');
            const scanAgainButton = document.getElementById('scan-again-button');

            let isProcessing = false;
            let activeRental = null;
            let resetTimer = null;

            function updateScannerState(label, text, tone = 'sky') {
                const badgeClasses = {
                    sky: 'bg-sky-100 text-sky-800',
                    amber: 'bg-amber-100 text-amber-800',
                    green: 'bg-green-100 text-green-800',
                    red: 'bg-red-100 text-red-800',
                };

                statusBadge.textContent = label;
                statusBadge.className = `inline-flex rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-wider ${badgeClasses[tone] ?? badgeClasses.sky}`;
                statusText.textContent = text;
            }

            function showMessage(message, tone = 'sky') {
                const toneClasses = {
                    sky: 'border-sky-200 bg-sky-50 text-sky-800',
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

            function resetResultPanel() {
                activeRental = null;
                clearMessage();
                confirmHandoverButton.classList.add('hidden');
                proceedToReturnLink.classList.add('hidden');
                proceedToReturnLink.removeAttribute('href');
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
                proceedToReturnLink.classList.add('hidden');

                if (data.next_action === 'handover') {
                    actionText.textContent = 'This rental is approved and ready for physical handover.';
                    confirmHandoverButton.classList.remove('hidden');
                } else if (data.next_action === 'return') {
                    actionText.textContent = 'This rental has already been handed over. Jump to the return form to complete the return workflow.';
                    proceedToReturnLink.href = `${rentalsIndexUrl}#rental-${data.id}`;
                    proceedToReturnLink.classList.remove('hidden');
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
                    confirmHandoverButton.textContent = 'Confirm Handover';
                }
            }

            confirmHandoverButton.addEventListener('click', confirmHandover);
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
