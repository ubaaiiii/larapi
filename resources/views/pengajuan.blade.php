@extends('layouts.master')
@section('title', 'Pengajuan')
@section('breadcrumb', 'Pengajuan')
@section('menu', 'Pengajuan')
@section('content')
<div class="intro-y flex items-center mt-4">
<h2 class="text-lg font-medium mr-auto">
Formulir Pengajuan
</h2>
</div>
<div class="grid grid-cols-12 gap-6 mt-5">
<div class="intro-y col-span-12 lg:col-span-6">
<!-- BEGIN: Input -->
<div class="intro-y box">
    <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200 dark:border-dark-5">
        <h2 class="font-medium text-base mr-auto">
            Data Nasabah
        </h2>
    </div>
    <div id="input" class="p-5">
        <div class="preview">
            <div>
                <label for="regular-form-1" class="form-label">Input Text</label>
                <input id="regular-form-1" type="text" class="form-control" placeholder="Input text">
            </div>
            <div class="mt-3">
                <label for="regular-form-2" class="form-label">Rounded</label>
                <input id="regular-form-2" type="text" class="form-control form-control-rounded"
                    placeholder="Rounded">
            </div>
            <div class="mt-3">
                <label for="regular-form-3" class="form-label">With Help</label>
                <input id="regular-form-3" type="text" class="form-control" placeholder="With help">
                <div class="form-help">Lorem Ipsum is simply dummy text of the printing and typesetting
                    industry.</div>
            </div>
            <div class="mt-3">
                <label for="regular-form-4" class="form-label">Password</label>
                <input id="regular-form-4" type="password" class="form-control" placeholder="Password">
            </div>
            <div class="mt-3">
                <label for="regular-form-5" class="form-label">Disabled</label>
                <input id="regular-form-5" type="text" class="form-control" placeholder="Disabled" disabled>
            </div>
        </div>
        <div class="source-code hidden">
            <button data-target="#copy-input" class="copy-code btn py-1 px-2 btn-outline-secondary"> <i
                    data-feather="file" class="w-4 h-4 mr-2"></i> Copy example code </button>
            <div class="overflow-y-auto mt-3 rounded-md">
                <pre id="copy-input"
                    class="source-preview"> <code class="text-xs p-0 rounded-md html pl-5 pt-8 pb-4 -mb-10 -mt-10"> HTMLOpenTagdivHTMLCloseTag HTMLOpenTaglabel for=&quot;regular-form-1&quot; class=&quot;form-label&quot;HTMLCloseTagInput TextHTMLOpenTag/labelHTMLCloseTag HTMLOpenTaginput id=&quot;regular-form-1&quot; type=&quot;text&quot; class=&quot;form-control&quot; placeholder=&quot;Input text&quot;HTMLCloseTag HTMLOpenTag/divHTMLCloseTag HTMLOpenTagdiv class=&quot;mt-3&quot;HTMLCloseTag HTMLOpenTaglabel for=&quot;regular-form-2&quot; class=&quot;form-label&quot;HTMLCloseTagRoundedHTMLOpenTag/labelHTMLCloseTag HTMLOpenTaginput id=&quot;regular-form-2&quot; type=&quot;text&quot; class=&quot;form-control form-control-rounded&quot; placeholder=&quot;Rounded&quot;HTMLCloseTag HTMLOpenTag/divHTMLCloseTag HTMLOpenTagdiv class=&quot;mt-3&quot;HTMLCloseTag HTMLOpenTaglabel for=&quot;regular-form-3&quot; class=&quot;form-label&quot;HTMLCloseTagWith HelpHTMLOpenTag/labelHTMLCloseTag HTMLOpenTaginput id=&quot;regular-form-3&quot; type=&quot;text&quot; class=&quot;form-control&quot; placeholder=&quot;With help&quot;HTMLCloseTag HTMLOpenTagdiv class=&quot;form-help&quot;HTMLCloseTagLorem Ipsum is simply dummy text of the printing and typesetting industry.HTMLOpenTag/divHTMLCloseTag HTMLOpenTag/divHTMLCloseTag HTMLOpenTagdiv class=&quot;mt-3&quot;HTMLCloseTag HTMLOpenTaglabel for=&quot;regular-form-4&quot; class=&quot;form-label&quot;HTMLCloseTagPasswordHTMLOpenTag/labelHTMLCloseTag HTMLOpenTaginput id=&quot;regular-form-4&quot; type=&quot;password&quot; class=&quot;form-control&quot; placeholder=&quot;Password&quot;HTMLCloseTag HTMLOpenTag/divHTMLCloseTag HTMLOpenTagdiv class=&quot;mt-3&quot;HTMLCloseTag HTMLOpenTaglabel for=&quot;regular-form-5&quot; class=&quot;form-label&quot;HTMLCloseTagDisabledHTMLOpenTag/labelHTMLCloseTag HTMLOpenTaginput id=&quot;regular-form-5&quot; type=&quot;text&quot; class=&quot;form-control&quot; placeholder=&quot;Disabled&quot; disabledHTMLCloseTag HTMLOpenTag/divHTMLCloseTag </code> </pre>
            </div>
        </div>
    </div>
</div>
<!-- END: Input -->


</div>
<div class="intro-y col-span-12 lg:col-span-6">
<div class="intro-y box">
    <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
        <h2 class="font-medium text-base mr-auto">
            Dokumen
        </h2>
    </div>
    <div id="multiple-file-upload" class="p-5">
        <div class="preview">
            <form action="/file-upload" class="dropzone">
                <div class="fallback">
                    <input name="file" type="file" multiple />
                </div>
                <div class="dz-message" data-dz-message>
                    <div class="text-lg font-medium">Drop files here or click to upload.</div>
                    <div class="text-gray-600"> This is just a demo dropzone. Selected files are <span
                            class="font-medium">not</span> actually uploaded. </div>
                </div>
            </form>
        </div>
        <div class="source-code hidden">
            <button data-target="#copy-multiple-file-upload"
                class="copy-code btn py-1 px-2 btn-outline-secondary"> <i data-feather="file"
                    class="w-4 h-4 mr-2"></i> Copy example code </button>
            <div class="overflow-y-auto mt-3 rounded-md">
                <pre id="copy-multiple-file-upload"
                    class="source-preview"> <code class="text-xs p-0 rounded-md html pl-5 pt-8 pb-4 -mb-10 -mt-10"> HTMLOpenTagform action=&quot;/file-upload&quot; class=&quot;dropzone&quot;HTMLCloseTag HTMLOpenTagdiv class=&quot;fallback&quot;HTMLCloseTag HTMLOpenTaginput name=&quot;file&quot; type=&quot;file&quot; multiple/HTMLCloseTag HTMLOpenTag/divHTMLCloseTag HTMLOpenTagdiv class=&quot;dz-message&quot; data-dz-messageHTMLCloseTag HTMLOpenTagdiv class=&quot;text-lg font-medium&quot;HTMLCloseTagDrop files here or click to upload.HTMLOpenTag/divHTMLCloseTag HTMLOpenTagdiv class=&quot;text-gray-600&quot;HTMLCloseTag This is just a demo dropzone. Selected files are HTMLOpenTagspan class=&quot;font-medium&quot;HTMLCloseTagnotHTMLOpenTag/spanHTMLCloseTag actually uploaded. HTMLOpenTag/divHTMLCloseTag HTMLOpenTag/divHTMLCloseTag HTMLOpenTag/formHTMLCloseTag </code> </pre>
            </div>
        </div>
    </div>
    <div class="p-5" id="responsive-table">
        <div class="preview">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">#</th>
                            <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">First Name</th>
                            <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">Last Name</th>
                            <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">Username</th>
                            <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">Email</th>
                            <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border-b whitespace-nowrap">1</td>
                            <td class="border-b whitespace-nowrap">Angelina</td>
                            <td class="border-b whitespace-nowrap">Jolie</td>
                            <td class="border-b whitespace-nowrap">@angelinajolie</td>
                            <td class="border-b whitespace-nowrap">angelinajolie@gmail.com</td>
                            <td class="border-b whitespace-nowrap">260 W. Storm Street New York, NY 10025.</td>
                        </tr>
                        <tr>
                            <td class="border-b whitespace-nowrap">2</td>
                            <td class="border-b whitespace-nowrap">Brad</td>
                            <td class="border-b whitespace-nowrap">Pitt</td>
                            <td class="border-b whitespace-nowrap">@bradpitt</td>
                            <td class="border-b whitespace-nowrap">bradpitt@gmail.com</td>
                            <td class="border-b whitespace-nowrap">47 Division St. Buffalo, NY 14241.</td>
                        </tr>
                        <tr>
                            <td class="border-b whitespace-nowrap">3</td>
                            <td class="border-b whitespace-nowrap">Charlie</td>
                            <td class="border-b whitespace-nowrap">Hunnam</td>
                            <td class="border-b whitespace-nowrap">@charliehunnam</td>
                            <td class="border-b whitespace-nowrap">charliehunnam@gmail.com</td>
                            <td class="border-b whitespace-nowrap">8023 Amerige Street Harriman, NY 10926.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="source-code hidden">
            <button data-target="#copy-responsive-table" class="copy-code btn py-1 px-2 btn-outline-secondary">
                <i data-feather="file" class="w-4 h-4 mr-2"></i> Copy example code </button>
            <div class="overflow-y-auto mt-3 rounded-md">
                <pre class="source-preview"
                    id="copy-responsive-table"> <code class="text-xs p-0 rounded-md html pl-5 pt-8 pb-4 -mb-10 -mt-10"> HTMLOpenTagdiv class=&quot;overflow-x-auto&quot;HTMLCloseTag HTMLOpenTagtable class=&quot;table&quot;HTMLCloseTag HTMLOpenTagtheadHTMLCloseTag HTMLOpenTagtrHTMLCloseTag HTMLOpenTagth class=&quot;border-b-2 dark:border-dark-5 whitespace-nowrap&quot;HTMLCloseTag#HTMLOpenTag/thHTMLCloseTag HTMLOpenTagth class=&quot;border-b-2 dark:border-dark-5 whitespace-nowrap&quot;HTMLCloseTagFirst NameHTMLOpenTag/thHTMLCloseTag HTMLOpenTagth class=&quot;border-b-2 dark:border-dark-5 whitespace-nowrap&quot;HTMLCloseTagLast NameHTMLOpenTag/thHTMLCloseTag HTMLOpenTagth class=&quot;border-b-2 dark:border-dark-5 whitespace-nowrap&quot;HTMLCloseTagUsernameHTMLOpenTag/thHTMLCloseTag HTMLOpenTagth class=&quot;border-b-2 dark:border-dark-5 whitespace-nowrap&quot;HTMLCloseTagEmailHTMLOpenTag/thHTMLCloseTag HTMLOpenTagth class=&quot;border-b-2 dark:border-dark-5 whitespace-nowrap&quot;HTMLCloseTagAddressHTMLOpenTag/thHTMLCloseTag HTMLOpenTag/trHTMLCloseTag HTMLOpenTag/theadHTMLCloseTag HTMLOpenTagtbodyHTMLCloseTag HTMLOpenTagtrHTMLCloseTag HTMLOpenTagtd class=&quot;border-b whitespace-nowrap&quot;HTMLCloseTag1HTMLOpenTag/tdHTMLCloseTag HTMLOpenTagtd class=&quot;border-b whitespace-nowrap&quot;HTMLCloseTagAngelinaHTMLOpenTag/tdHTMLCloseTag HTMLOpenTagtd class=&quot;border-b whitespace-nowrap&quot;HTMLCloseTagJolieHTMLOpenTag/tdHTMLCloseTag HTMLOpenTagtd class=&quot;border-b whitespace-nowrap&quot;HTMLCloseTag@angelinajolieHTMLOpenTag/tdHTMLCloseTag HTMLOpenTagtd class=&quot;border-b whitespace-nowrap&quot;HTMLCloseTagangelinajolie@gmail.comHTMLOpenTag/tdHTMLCloseTag HTMLOpenTagtd class=&quot;border-b whitespace-nowrap&quot;HTMLCloseTag260 W. Storm Street New York, NY 10025.HTMLOpenTag/tdHTMLCloseTag HTMLOpenTag/trHTMLCloseTag HTMLOpenTagtrHTMLCloseTag HTMLOpenTagtd class=&quot;border-b whitespace-nowrap&quot;HTMLCloseTag2HTMLOpenTag/tdHTMLCloseTag HTMLOpenTagtd class=&quot;border-b whitespace-nowrap&quot;HTMLCloseTagBradHTMLOpenTag/tdHTMLCloseTag HTMLOpenTagtd class=&quot;border-b whitespace-nowrap&quot;HTMLCloseTagPittHTMLOpenTag/tdHTMLCloseTag HTMLOpenTagtd class=&quot;border-b whitespace-nowrap&quot;HTMLCloseTag@bradpittHTMLOpenTag/tdHTMLCloseTag HTMLOpenTagtd class=&quot;border-b whitespace-nowrap&quot;HTMLCloseTagbradpitt@gmail.comHTMLOpenTag/tdHTMLCloseTag HTMLOpenTagtd class=&quot;border-b whitespace-nowrap&quot;HTMLCloseTag47 Division St. Buffalo, NY 14241.HTMLOpenTag/tdHTMLCloseTag HTMLOpenTag/trHTMLCloseTag HTMLOpenTagtrHTMLCloseTag HTMLOpenTagtd class=&quot;border-b whitespace-nowrap&quot;HTMLCloseTag3HTMLOpenTag/tdHTMLCloseTag HTMLOpenTagtd class=&quot;border-b whitespace-nowrap&quot;HTMLCloseTagCharlieHTMLOpenTag/tdHTMLCloseTag HTMLOpenTagtd class=&quot;border-b whitespace-nowrap&quot;HTMLCloseTagHunnamHTMLOpenTag/tdHTMLCloseTag HTMLOpenTagtd class=&quot;border-b whitespace-nowrap&quot;HTMLCloseTag@charliehunnamHTMLOpenTag/tdHTMLCloseTag HTMLOpenTagtd class=&quot;border-b whitespace-nowrap&quot;HTMLCloseTagcharliehunnam@gmail.comHTMLOpenTag/tdHTMLCloseTag HTMLOpenTagtd class=&quot;border-b whitespace-nowrap&quot;HTMLCloseTag8023 Amerige Street Harriman, NY 10926.HTMLOpenTag/tdHTMLCloseTag HTMLOpenTag/trHTMLCloseTag HTMLOpenTag/tbodyHTMLCloseTag HTMLOpenTag/tableHTMLCloseTag HTMLOpenTag/divHTMLCloseTag </code> </pre>
            </div>
        </div>
    </div>
</div>

</div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {

    });
</script>
@endsection
