<!-- Submit Intent to Submit Modal -->
<div class="modal fade" id="submitIntentModal" tabindex="-1" aria-labelledby="submitIntentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="submitIntentModalLabel">
                    <i class="fas fa-calendar-check me-2 text-warning"></i>Declare Intent to Submit
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('thesis.submit-intent') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p class="text-muted small mb-3">
                        Declaring your intent to submit notifies your supervisor and department of your planned submission date.
                        Ensure you have discussed this date with your supervisor before proceeding.
                    </p>

                    <div class="mb-3">
                        <label for="intentDate" class="form-label fw-medium">
                            Planned Submission Date <span class="text-danger">*</span>
                        </label>
                        <input type="date" class="form-control" id="intentDate" name="intent_to_submit_date"
                               min="{{ now()->addDay()->format('Y-m-d') }}" required>
                        <div class="form-text">Must be a future date.</div>
                    </div>

                    <div class="alert alert-warning py-2 small">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        Once submitted, your supervisor and the department will be notified of this date.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-calendar-check me-2"></i>Declare Intent
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
