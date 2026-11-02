<!-- Submit Ethics Modal -->
<div class="modal fade" id="submitEthicsModal" tabindex="-1" aria-labelledby="submitEthicsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="submitEthicsModalLabel">Submit Ethics Approval Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo e(route('thesis.submit-ethics')); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="ethicsDocuments" class="form-label">Ethics Approval Documents <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="ethicsDocuments" name="documents[]" multiple required
                               accept=".pdf,.doc,.docx">
                        <div class="form-text">
                            Upload completed ethics approval form, participant consent forms, and any supporting documentation (PDF, DOC, DOCX - Max 10MB each)
                        </div>
                    </div>

                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Important:</strong> Ensure all required ethics approvals are obtained before data collection begins. Your research cannot proceed without proper ethical clearance.
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Ethics documents will be reviewed by the Directorate of Research Innovation and Extension <a href="https://drie.jooust.ac.ke/">(DRIE)</a>. This process may take 4-6 weeks.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Submit Ethics Form</button>
                </div>
            </form>
        </div>
    </div>
</div><?php /**PATH C:\Users\Admin\Desktop\Version2.2\resources\views\thesis\modals\submit-ethics.blade.php ENDPATH**/ ?>