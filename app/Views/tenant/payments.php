<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Payments - RentBase</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <?= view('components/modern_styles') ?>
</head>
<body>
    <div class="main-wrapper">
        <?php $active = 'payments'; ?>
        <?= view('components/tenant_navbar', ['username' => $username, 'active' => $active]) ?>
        
        <h1 class="page-title">My Payments</h1>
        <p class="page-subtitle">Submit rent payments and view payment history</p>
        
        <div class="row">
            <div class="col-md-4">
                <div class="content-section">
                    <div class="section-header">
                        <h3 class="section-title">Submit Payment</h3>
                    </div>
                    
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle me-2"></i><?= esc(session()->getFlashdata('success')) ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-circle me-2"></i><?= esc(session()->getFlashdata('error')) ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="post" action="<?= base_url('tenant/payments/submit') ?>">
                        <?= csrf_field() ?>
                        <div class="form-group">
                            <label class="form-label">Amount</label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="number" class="form-control" name="amount" step="0.01" required placeholder="0.00">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Payment Method</label>
                            <select class="form-select" name="payment_method" required>
                                <option value="">Select payment method</option>
                                <option value="Cash">Cash</option>
                                <option value="Bank Transfer">Bank Transfer</option>
                                <option value="GCash">GCash</option>
                                <option value="PayMaya">PayMaya</option>
                                <option value="Credit Card">Credit Card</option>
                                <option value="Debit Card">Debit Card</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Reference Number</label>
                            <input type="text" class="form-control" name="reference_number" placeholder="Transaction or reference number">
                            <small class="form-text text-muted">For bank transfers or online payments</small>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Notes (Optional)</label>
                            <textarea class="form-control" name="notes" rows="3" placeholder="Additional notes or remarks..."></textarea>
                        </div>
                        <button type="submit" class="btn-primary-custom w-100">
                            <i class="bi bi-credit-card"></i> Submit Payment
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="col-md-8">
                <div class="content-section">
                    <div class="section-header">
                        <h3 class="section-title">Payment History</h3>
                        <div>
                            <span class="badge-primary"><i class="bi bi-cash-coin"></i> <?= count($payments ?? []) ?> Total Payments</span>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table-modern">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Reference</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($payments)): ?>
                                    <?php foreach ($payments as $payment): ?>
                                        <tr>
                                            <td><?= date('M d, Y', strtotime($payment['payment_date'])) ?></td>
                                            <td><strong>₱<?= number_format($payment['amount'], 2) ?></strong></td>
                                            <td><?= esc($payment['payment_method']) ?></td>
                                            <td><?= esc($payment['reference_number'] ?: '-') ?></td>
                                            <td>
                                                <span class="badge-success"><i class="bi bi-check-circle"></i> Recorded</span>
                                            </td>
                                            <td>
                                                <button class="action-btn btn-view" onclick="viewPayment('<?= date('M d, Y', strtotime($payment['payment_date'])) ?>', '<?= number_format($payment['amount'], 2) ?>', '<?= esc($payment['payment_method']) ?>', '<?= esc($payment['reference_number']) ?>', '<?= esc($payment['notes']) ?>')">
                                                    <i class="bi bi-eye"></i> View
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center" style="padding: 40px;">
                                            <i class="bi bi-credit-card" style="font-size: 48px; color: #cbd5e1;"></i>
                                            <p style="color: #64748b; margin-top: 16px;">No payments submitted yet.</p>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- View Payment Modal -->
    <div class="modal fade" id="viewPaymentModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Payment Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label"><strong>Date & Time:</strong></label>
                            <p id="view_date"></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><strong>Amount:</strong></label>
                            <p id="view_amount" style="font-size: 24px; font-weight: 600; color: #10b981;"></p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label"><strong>Payment Method:</strong></label>
                            <p id="view_method"></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><strong>Reference Number:</strong></label>
                            <p id="view_reference"></p>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><strong>Notes:</strong></label>
                        <p id="view_notes" style="white-space: pre-wrap;"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function viewPayment(date, amount, method, reference, notes) {
            document.getElementById('view_date').textContent = date;
            document.getElementById('view_amount').textContent = '₱' + amount;
            document.getElementById('view_method').textContent = method;
            document.getElementById('view_reference').textContent = reference || 'N/A';
            document.getElementById('view_notes').textContent = notes || 'No notes provided';
            
            new bootstrap.Modal(document.getElementById('viewPaymentModal')).show();
        }
    </script>
</body>
</html>
