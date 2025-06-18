<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Delete Inquiry') }}
        </h2>
    </x-slot>

    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Delete Inquiry</h1>
                <p class="text-gray-600">Please confirm deletion of inquiry and provide a reason</p>
            </div>

            <!-- Inquiry Details Card -->
            <div class="bg-white rounded-2xl shadow-lg mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-info-circle mr-2 text-blue-600"></i>Inquiry Details
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Inquiry ID</label>
                            <p class="text-gray-900 bg-gray-50 p-3 rounded-lg">{{ $inquiry->I_ID }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                            @php
                                $statusColors = [
                                    'Pending' => 'bg-yellow-100 text-yellow-800',
                                    'In Progress' => 'bg-blue-100 text-blue-800',
                                    'Resolved' => 'bg-green-100 text-green-800',
                                    'Closed' => 'bg-gray-100 text-gray-800',
                                    'Rejected' => 'bg-red-100 text-red-800'
                                ];
                                $colorClass = $statusColors[$inquiry->I_Status] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="inline-flex px-3 py-2 text-sm font-semibold rounded-full {{ $colorClass }}">
                                {{ $inquiry->I_Status }}
                            </span>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Title</label>
                            <p class="text-gray-900 bg-gray-50 p-3 rounded-lg">{{ $inquiry->I_Title }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Category</label>
                            <p class="text-gray-900 bg-gray-50 p-3 rounded-lg">{{ $inquiry->I_Category }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Date Submitted</label>
                            <p class="text-gray-900 bg-gray-50 p-3 rounded-lg">{{ $inquiry->I_Date ? \Carbon\Carbon::parse($inquiry->I_Date)->format('d/m/Y') : 'N/A' }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                            <p class="text-gray-900 bg-gray-50 p-3 rounded-lg">{{ $inquiry->I_Description }}</p>
                        </div>
                        @if($inquiry->publicUser)
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Submitted By</label>
                            <p class="text-gray-900 bg-gray-50 p-3 rounded-lg">{{ $inquiry->publicUser->PU_Name }} ({{ $inquiry->publicUser->PU_Email }})</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Delete Confirmation Form -->
            <div class="bg-white rounded-2xl shadow-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-red-600 flex items-center">
                        <i class="fas fa-exclamation-triangle mr-2"></i>Confirm Deletion
                    </h3>
                </div>
                <div class="p-6">
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-triangle text-red-500 mt-1 mr-3"></i>
                            <div>
                                <h4 class="text-red-800 font-semibold mb-2">Warning: This action cannot be undone</h4>
                                <p class="text-red-700 text-sm">
                                    Deleting this inquiry will permanently remove all associated data including:
                                </p>
                                <ul class="text-red-700 text-sm mt-2 ml-4 list-disc">
                                    <li>Inquiry details and description</li>
                                    <li>Attached files and documents</li>
                                    <li>Progress history and updates</li>
                                    <li>All related records</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <form id="deleteForm" action="{{ route('inquiries.destroy', $inquiry->I_ID) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('DELETE')
                        
                        <!-- Deletion Reason -->
                        <div>
                            <label for="deletion_reason" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-comment-alt mr-1 text-red-500"></i>Reason for Deletion <span class="text-red-500">*</span>
                            </label>
                            <select id="deletion_reason" name="deletion_reason" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors duration-200">
                                <option value="">Select a reason...</option>
                                <option value="Duplicate inquiry">Duplicate inquiry</option>
                                <option value="Invalid or incomplete information">Invalid or incomplete information</option>
                                <option value="Spam or inappropriate content">Spam or inappropriate content</option>
                                <option value="Request from inquirer">Request from inquirer</option>
                                <option value="Outside jurisdiction">Outside jurisdiction</option>
                                <option value="Technical error">Technical error</option>
                                <option value="Policy violation">Policy violation</option>
                                <option value="Other">Other (specify below)</option>
                            </select>
                            @error('deletion_reason')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Additional Comments -->
                        <div id="additional_comments_section" style="display: none;">
                            <label for="additional_comments" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-file-alt mr-1 text-gray-500"></i>Additional Comments <span class="text-red-500">*</span>
                            </label>
                            <textarea id="additional_comments" name="additional_comments" rows="4" 
                                      placeholder="Please provide additional details about the reason for deletion..."
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors duration-200 resize-none"></textarea>
                            @error('additional_comments')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Optional Comments -->
                        <div id="optional_comments_section">
                            <label for="optional_comments" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-file-alt mr-1 text-gray-500"></i>Additional Comments (Optional)
                            </label>
                            <textarea id="optional_comments" name="optional_comments" rows="3" 
                                      placeholder="Any additional notes or comments about this deletion..."
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors duration-200 resize-none"></textarea>
                        </div>

                        <!-- Confirmation Checkbox -->
                        <div class="flex items-start space-x-3">
                            <input type="checkbox" id="confirm_deletion" name="confirm_deletion" required
                                   class="mt-1 h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                            <label for="confirm_deletion" class="text-sm text-gray-700">
                                I understand that this action is permanent and cannot be undone. I confirm that I want to delete this inquiry.
                                <span class="text-red-500">*</span>
                            </label>
                        </div>
                        @error('confirm_deletion')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror

                        <!-- Form Actions -->
                        <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4 pt-6 border-t border-gray-200">
                            <a href="{{ route('inquiries.show', $inquiry->I_ID) }}" 
                               class="inline-flex items-center justify-center px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                                <i class="fas fa-arrow-left mr-2"></i>Cancel
                            </a>
                            <button type="submit" id="deleteButton" disabled
                                    class="inline-flex items-center justify-center px-6 py-3 bg-red-600 hover:bg-red-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                                <i class="fas fa-trash mr-2"></i>Delete Inquiry
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for form validation and interactions -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deletionReasonSelect = document.getElementById('deletion_reason');
            const additionalCommentsSection = document.getElementById('additional_comments_section');
            const optionalCommentsSection = document.getElementById('optional_comments_section');
            const additionalCommentsTextarea = document.getElementById('additional_comments');
            const confirmCheckbox = document.getElementById('confirm_deletion');
            const deleteButton = document.getElementById('deleteButton');
            const deleteForm = document.getElementById('deleteForm');

            // Show/hide additional comments based on reason selection
            deletionReasonSelect.addEventListener('change', function() {
                if (this.value === 'Other') {
                    additionalCommentsSection.style.display = 'block';
                    optionalCommentsSection.style.display = 'none';
                    additionalCommentsTextarea.required = true;
                } else if (this.value) {
                    additionalCommentsSection.style.display = 'none';
                    optionalCommentsSection.style.display = 'block';
                    additionalCommentsTextarea.required = false;
                    additionalCommentsTextarea.value = '';
                } else {
                    additionalCommentsSection.style.display = 'none';
                    optionalCommentsSection.style.display = 'none';
                    additionalCommentsTextarea.required = false;
                    additionalCommentsTextarea.value = '';
                }
                validateForm();
            });

            // Enable/disable delete button based on form validation
            function validateForm() {
                const reasonSelected = deletionReasonSelect.value !== '';
                const confirmChecked = confirmCheckbox.checked;
                const additionalCommentsValid = !additionalCommentsTextarea.required || additionalCommentsTextarea.value.trim() !== '';
                
                deleteButton.disabled = !(reasonSelected && confirmChecked && additionalCommentsValid);
            }

            // Add event listeners for real-time validation
            confirmCheckbox.addEventListener('change', validateForm);
            additionalCommentsTextarea.addEventListener('input', validateForm);

            // Confirm before submitting
            deleteForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                if (confirm('Are you absolutely sure you want to delete this inquiry? This action cannot be undone.')) {
                    // Show loading state
                    deleteButton.disabled = true;
                    deleteButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Deleting...';
                    
                    // Submit the form
                    this.submit();
                }
            });
        });
    </script>
</x-app-layout>