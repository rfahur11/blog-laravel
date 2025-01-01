<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Tulisan
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-2xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                Tambah Data Tulisan
                            </h2>

                            <p class="mt-1 text-sm text-gray-600">
                                Silakan melakukan penambahan data
                            </p>
                        </header>

                        <form method="post" action="{{ route('member.blogs.store') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
                            @csrf
                            <div>
                                <x-input-label for="title" value="Title" />
                                <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" value="{{ old('title') }}"/>                            
                            </div>
                            <div>
                                <x-input-label for="description" value="Description" />
                                <x-text-input id="description" name="description" type="text" class="mt-1 block w-full" value="{{ old('description') }}" />                            
                            </div>
                            <div>
                                <x-input-label for="file_input" value="thumbnail" />
                                <input type="file" class="w-full border border-gray-300 rounded-sm" name="thumbnail"/>
                            </div>
                            <div>
                                <x-textarea-trix value="{!! old('content') !!}" id="x" name="content"></x-textarea-trix>
                            </div>
                            <div>
                             <x-select name="status">
                                <option value="draft" {{ (old('status')=='draft')?'selected':'' }}>Simpan Sebagai Draft</option>
                                <option value="publish" {{ (old('status')=='publish')?'selected':'' }}>Simpan Sebagai Publish</option>
                          
                             </x-select>   

                            </div>
                            <div class="flex items-center gap-4">
                                <a href="{{ route("member.blogs.index")}}">

                                    <x-secondary-button>Kembali</x-secondary-button>
                                </a>
                                <x-primary-button>Simpan</x-primary-button>
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>