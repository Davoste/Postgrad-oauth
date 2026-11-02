<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Postgraduate Admission Portal</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @include('components.flash-messages')
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('applications.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block font-medium text-sm text-gray-700">Target Program</label>
                        <select name="program" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full mt-1">
                            <option value="">-- Select Program --</option>
                            <option value="MSc Computer Science">MSc Computer Science</option>
                            <option value="PhD Data Engineering">PhD Data Engineering</option>
                            <option value="MPhil Public Health">MPhil Public Health</option>
                        </select>
                        @error('program') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block font-medium text-sm text-gray-700">Supporting Documents (PDF/DOCX)</label>
                        <input type="file" name="documents[]" multiple accept=".pdf,.docx,.doc" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"/>
                        <p class="text-xs text-gray-500 mt-1">Upload: CV, Transcripts, Research Concept (Max 10MB each)</p>
                        @error('documents') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        @error('documents.*') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                            Submit Application
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>