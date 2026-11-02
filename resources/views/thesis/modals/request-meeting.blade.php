<div class="modal fade" id="requestMeetingModal" tabindex="-1" aria-labelledby="requestMeetingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="requestMeetingModalLabel">Request Supervision Meeting</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('thesis.request-meeting') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Requested Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="meeting_date" required
                               min="{{ now()->addDay()->format('Y-m-d') }}">
                        <div class="form-text">Select a date at least 24 hours from now.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Agenda <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="agenda" rows="4" required
                                  placeholder="What would you like to discuss? Include any specific questions or issues."></textarea>
                    </div>
                    <div class="alert alert-info small">
                        <i class="fas fa-info-circle me-2"></i>
                        Your supervisor will be notified. A meeting date will be confirmed at the supervisor's discretion.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Request Meeting</button>
                </div>
            </form>
        </div>
    </div>
</div>
