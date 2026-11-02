<!-- Submit Concept Modal -->
<div class="modal fade" id="submitConceptModal" tabindex="-1" aria-labelledby="submitConceptModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="submitConceptModalLabel">Submit Thesis Concept</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo e(route('thesis.submit-concept')); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="conceptTitle" class="form-label">Thesis Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="conceptTitle" name="title" required
                               placeholder="Enter your proposed thesis title">
                    </div>

                    <div class="mb-3">
                        <label for="conceptDescription" class="form-label">Concept Description <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="conceptDescription" name="description" rows="6" required
                                  placeholder="Describe your thesis concept, research objectives, methodology, and expected outcomes"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="conceptDocuments" class="form-label">Supporting Documents</label>
                        <input type="file" class="form-control" id="conceptDocuments" name="documents[]" multiple
                               accept=".pdf,.doc,.docx">
                        <div class="form-text">
                            Upload concept paper, preliminary literature review, or other supporting documents (PDF, DOC, DOCX - Max 10MB each)
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Your concept will be reviewed by the departmental panel. You will receive feedback within 2-3 weeks.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit Concept</button>
                </div>
            </form>
        </div>
    </div>
</div><?php /**PATH C:\Users\Admin\Desktop\Version2.2\resources\views\thesis\modals\submit-concept.blade.php ENDPATH**/ ?>