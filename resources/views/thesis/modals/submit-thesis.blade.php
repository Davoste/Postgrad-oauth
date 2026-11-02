<!-- Submit Thesis Modal -->
<div class="modal fade" id="submitThesisModal" tabindex="-1" aria-labelledby="submitThesisModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="submitThesisModalLabel">Submit Final Thesis</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('thesis.submit-thesis') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="thesisTitle" class="form-label">Thesis Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="thesisTitle" name="title" required
                               value="{{ $thesis->title ?? '' }}" placeholder="Enter your final thesis title">
                    </div>

                    <div class="mb-3">
                        <label for="thesisDocuments" class="form-label">Thesis Document <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="thesisDocuments" name="documents[]" multiple required
                               accept=".pdf,.doc,.docx">
                        <div class="form-text">
                            Upload your complete thesis document, including all chapters, references, appendices, and any supplementary materials (PDF, DOC, DOCX - Max 50MB each)
                        </div>
                    </div>

                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Final Submission:</strong> This is your final thesis submission. Ensure all corrections from previous reviews have been incorporated and your thesis meets all formatting requirements.
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Your thesis will be forwarded to external examiners. The examination process typically takes 8-12 weeks.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info">Submit Thesis</button>
                </div>
            </form>
        </div>
    </div>
</div>