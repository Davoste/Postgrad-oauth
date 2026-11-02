<!-- Submit Proposal Modal -->
<div class="modal fade" id="submitProposalModal" tabindex="-1" aria-labelledby="submitProposalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="submitProposalModalLabel">Submit Thesis Proposal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('thesis.submit-proposal') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="proposalTitle" class="form-label">Thesis Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="proposalTitle" name="title" required
                               value="{{ $thesis->title ?? '' }}" placeholder="Enter your thesis title">
                    </div>

                    <div class="mb-3">
                        <label for="proposalDescription" class="form-label">Proposal Description <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="proposalDescription" name="description" rows="8" required
                                  placeholder="Provide detailed description of your research proposal including:
- Research problem and objectives
- Literature review summary
- Research methodology and design
- Expected results and contributions
- Timeline and milestones"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="proposalDocuments" class="form-label">Proposal Documents <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="proposalDocuments" name="documents[]" multiple required
                               accept=".pdf,.doc,.docx">
                        <div class="form-text">
                            Upload complete proposal document, research timeline, budget (if applicable) (PDF, DOC, DOCX - Max 10MB each)
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Your proposal will be reviewed by your supervisor and the departmental committee. Approval is required before proceeding to the next stage.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Submit Proposal</button>
                </div>
            </form>
        </div>
    </div>
</div>