<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Hasil Pengerjaan: {{ $material->title }}
            </h2>
            <a href="{{ route('dosen.class.show', $material->classroom_id) }}" class="text-sm text-gray-600 hover:text-gray-900">
                &larr; Kembali ke Kelas
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 overflow-x-auto">
                    
                    @if($groupedResults->isEmpty())
                        <div class="text-center py-10 text-gray-500">
                            Belum ada mahasiswa yang mengerjakan materi ini.
                        </div>
                    @else
                        <table class="min-w-full border-collapse block md:table">
                            <thead class="block md:table-header-group">
                                <tr class="border border-gray-200 md:border-none block md:table-row absolute -top-full md:top-auto -left-full md:left-auto  md:relative ">
                                    <th class="bg-gray-100 p-2 text-gray-700 font-bold md:border md:border-grey-500 text-left block md:table-cell w-48">
                                        Mahasiswa
                                    </th>
                                    <th class="bg-gray-100 p-2 text-gray-700 font-bold md:border md:border-grey-500 text-center block md:table-cell w-24">
                                        Total Skor
                                    </th>
                                    {{-- Loop Header Soal --}}
                                    @foreach($questions as $index => $question)
                                        <th class="bg-gray-50 p-2 text-gray-600 font-bold md:border md:border-grey-500 text-left block md:table-cell min-w-[200px]">
                                            <div class="text-xs text-gray-400">Soal {{ $index + 1 }}</div>
                                            <div class="truncate w-48" title="{{ $question->question_text }}">
                                                {{ Str::limit($question->question_text, 40) }}
                                            </div>
                                            <div class="text-xs text-green-600 font-mono mt-1">
                                                Key: {{ Str::limit($question->correct_answer_key, 20) }}
                                            </div>
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="block md:table-row-group">
                                @foreach($groupedResults as $result)
                                    <tr class="bg-white border border-gray-200 md:border-none block md:table-row hover:bg-gray-50">
                                        {{-- Nama Mahasiswa --}}
                                        <td class="p-2 md:border md:border-grey-500 text-left block md:table-cell">
                                            <span class="inline-block w-1/3 md:hidden font-bold">Nama</span>
                                            <div class="font-medium">{{ $result['student']->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $result['student']->nim }}</div>
                                        </td>
                                        
                                        {{-- Total Skor --}}
                                        <td class="p-2 md:border md:border-grey-500 text-center block md:table-cell">
                                            <span class="inline-block w-1/3 md:hidden font-bold">Skor</span>
                                            <span class="px-2 py-1 rounded text-white font-bold {{ $result['total_score'] >= 70 ? 'bg-green-500' : 'bg-red-500' }}">
                                                {{ $result['total_score'] }}
                                            </span>
                                        </td>

                                        {{-- Jawaban per Soal --}}
                                        @foreach($questions as $question)
                                            @php
                                                // Cek apakah siswa menjawab soal ini
                                                $answer = $result['answers']->get($question->id);
                                            @endphp
                                            <td class="p-2 md:border md:border-grey-500 text-left block md:table-cell text-sm">
                                                <span class="inline-block w-1/3 md:hidden font-bold">Soal ID {{ $question->id }}</span>
                                                
                                                @if($answer)
                                                    <div class="mb-1">
                                                        <span class="font-semibold text-xs text-gray-500">Jawab:</span><br>
                                                        <span class="font-mono text-blue-700 bg-blue-50 px-1 rounded break-all">
                                                            {{ $answer->answer_text }}
                                                        </span>
                                                    </div>
                                                    
                                                    @if($answer->system_feedback)
                                                        <div class="text-xs text-red-500 mt-1 italic border-t pt-1">
                                                            {{ Str::limit($answer->system_feedback, 50) }}
                                                        </div>
                                                    @endif

                                                    <div class="mt-1 text-xs font-bold {{ $answer->score > 0 ? 'text-green-600' : 'text-red-600' }}">
                                                        Nilai: {{ $answer->score }}
                                                    </div>
                                                @else
                                                    <span class="text-gray-400 italic">Tidak dijawab</span>
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>