<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} - scribe</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        dark: {
                            100: '#d1d5db',
                            200: '#9ca3af',
                            300: '#6b7280',
                            400: '#4b5563',
                            500: '#374151',
                            600: '#1f2937',
                            700: '#111827',
                            800: '#0d1424',
                            900: '#030712',
                        }
                    }
                }
            }
        }
    </script>
    @php
    $prismTheme = config('scribe.syntax_highlighting.theme', 'prism-tomorrow');
    @endphp
    <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/themes/prism-tomorrow.min.css" rel="stylesheet" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
        }

        Ensure Prism styles are not overridden
        pre[class*="language-"],
        code[class*="language-"] {
            color: inherit;
            background: #050b19;
            text-shadow: none;
            font-family: Consolas, Monaco, 'Andale Mono', 'Ubuntu Mono', monospace;
            text-align: left;
            white-space: pre;
            word-spacing: normal;
            word-break: normal;
            word-wrap: normal;
            line-height: 1.5;
            tab-size: 4;
            hyphens: none;
        }

        pre[class*="language-"] {
            padding: 1em;
            margin: .5em 0;
            overflow: auto;
            border-radius: 0.3em;
            background: #050b19;
            border: 1px solid #1e283d;
        }

        /* Custom scrollbar styles */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #1e1e2e;
        }
        ::-webkit-scrollbar-thumb {
            background: #3f3f46;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #52525b;
        }

        /* Ensure code blocks have proper padding and margin */
        pre[class*="language-"] {
            padding: 1em;
            margin: .5em 0;
            overflow: auto;
            border-radius: 0.3em;
        }

        /* Adjust inline code styling */
        :not(pre) > code[class*="language-"] {
            padding: .1em .3em;
            border-radius: .3em;
            white-space: normal;
        }

        .markdown-body {
            color: #e5e7eb;
            line-height: 1.625;
        }

        /* Headings */
        .markdown-body h1 {
            font-size: 2.25rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            margin-top: 3rem;
            color: #ffffff;
        }

        .markdown-body h2 {
            font-size: 1.875rem;
            font-weight: 600;
            margin-bottom: 1rem;
            margin-top: 2rem;
            color: #ffffff;
        }

        .markdown-body h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
            margin-top: 1.5rem;
            color: #ffffff;
        }

        .markdown-body h4 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            margin-top: 1rem;
            color: #ffffff;
        }

        .markdown-body h5 {
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            margin-top: 1rem;
            color: #ffffff;
        }

        .markdown-body h6 {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            margin-top: 1rem;
            color: #ffffff;
        }

        /* Emphasis */
        .markdown-body em {
            font-style: italic;
        }

        .markdown-body strong {
            font-weight: 700;
            color: #ffffff;
        }

        /* Blockquotes */
        .markdown-body blockquote {
            border-left: 4px solid #6b7280;
            padding-left: 1rem;
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
            margin-bottom: 1rem;
            font-style: italic;
            color: #d1d5db;
        }

        /* Lists */
        .markdown-body ul, .markdown-body ol {
            padding-left: 2rem;
            margin-bottom: 1rem;
        }

        .markdown-body ul {
            list-style-type: disc;
        }

        .markdown-body ol {
            list-style-type: decimal;
        }

        .markdown-body li > ul, .markdown-body li > ol {
            margin-bottom: 0;
            margin-top: 0.5rem;
        }

        /* Links */
        .markdown-body a {
            color: #60a5fa;
            text-decoration: none;
        }

        .markdown-body a:hover {
            text-decoration: underline;
        }

        /* Images */
        .markdown-body img {
            max-width: 100%;
            height: auto;
            margin-bottom: 1rem;
            border-radius: 0.25rem;
        }

        /* Inline Code */
        .markdown-body :not(pre) > code {
            background-color: #050B19;
            color: #e5e7eb;
            border-radius: 0.25rem;
            padding: 0.125rem 0.25rem;
            font-size: 0.875rem;
        }

        /* Tables */
        .markdown-body table {
            width: 100%;
            margin-bottom: 1rem;
            border-collapse: collapse;
        }

        .markdown-body th, .markdown-body td {
            border: 1px solid #4b5563;
            padding: 0.5rem 1rem;
            text-align: left;
        }

        .markdown-body th {
            background-color: #374151;
            font-weight: 600;
        }

        /* Horizontal Rule */
        .markdown-body hr {
            margin-top: 2rem;
            margin-bottom: 2rem;
            border: 0;
            border-top: 1px solid #4b5563;
        }

        /* Task Lists */
        .markdown-body input[type="checkbox"] {
            margin-right: 0.5rem;
        }

        /* Strikethrough */
        .markdown-body del {
            text-decoration: line-through;
            color: #9ca3af;
        }

        /* Footnotes */
        .markdown-body .footnote {
            font-size: 0.875rem;
            color: #9ca3af;
        }

        .markdown-body .footnotes {
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 1px solid #4b5563;
        }

        /* HTML in Markdown */
        .markdown-body .custom-block {
            padding: 1rem;
            border-radius: 0.25rem;
            margin-bottom: 1rem;
        }

        .markdown-body .custom-block.info {
            background-color: #1e3a8a;
            color: #bfdbfe;
        }

        .markdown-body .custom-block.warning {
            background-color: #854d0e;
            color: #fef08a;
        }

        .markdown-body .custom-block.danger {
            background-color: #7f1d1d;
            color: #fecaca;
        }

        /* General text and spacing */
        .markdown-body p {
            margin-bottom: 1rem;
        }

        .markdown-body * + h1,
        .markdown-body * + h2,
        .markdown-body * + h3 {
            margin-top: 3rem;
        }

        .markdown-body * + h4,
        .markdown-body * + h5,
        .markdown-body * + h6 {
            margin-top: 2rem;
        }
    </style>
</head>
<body class="bg-slate-900 text-dark-100 min-h-screen flex flex-col">
    <!-- Top Navigation -->
    <nav class="bg-slate-900 p-4 max-w-7xl w-full mx-auto mb-12">
        <div class="mx-auto flex justify-between items-center">
            <a href="#" class="flex items-center space-x-3">
                @if(!empty(config('scribe.logo.path')))
                    <img src="{{ asset(config('scribe.logo.path')) }}" alt="{{ config('scribe.logo.alt') }}" class="h-8 w-8">
                @else
                    <svg fill="none" height="48" viewBox="0 0 40 48" width="40" xmlns="http://www.w3.org/2000/svg"><g clip-rule="evenodd" fill="#fff" fill-rule="evenodd"><path d="m4.29111 28.6025c.90712.9019.9071 2.3641-.00003 3.266l-.04646.0462c-.90713.9019-2.37788.9018-3.284994-.0001-.9071177-.9019-.9071038-2.3641.000031-3.266l.046453-.0462c.90714-.9019 2.37788-.9018 3.285.0001z" opacity=".5"/><path d="m15.2112 29.298c.9055.9035.9028 2.3658-.006 3.266l-6.43424 6.3741c-.90876.9002-2.3795.8976-3.28499-.0059-.90548-.9035-.90282-2.3658.00594-3.266l6.43429-6.3741c.9088-.9002 2.3795-.8976 3.285.0059z" opacity=".7"/><path d="m23.9431 40.2706c.7116-1.0613 2.1538-1.348 3.2212-.6405l.1394.0923c1.0674.7075 1.3558 2.1414.6442 3.2026-.7116 1.0613-2.1538 1.3481-3.2212.6406l-.1394-.0924c-1.0674-.7075-1.3558-2.1414-.6442-3.2026z" opacity=".5"/><path d="m39.2728 28.4638c.9072.9019.9072 2.3641 0 3.266l-3.159 3.1408c-.9072.9019-2.3779.9019-3.285 0s-.9071-2.3641 0-3.266l3.1591-3.1408c.9071-.9019 2.3778-.9019 3.2849 0z" opacity=".7"/><path d="m39.3197 16.8473c.9071.9019.9071 2.3642 0 3.266l-23.3446 23.2098c-.9071.9019-2.3779.9019-3.285 0s-.9071-2.3641 0-3.266l23.3446-23.2098c.9071-.9019 2.3778-.9019 3.285 0z"/><path d="m34.905 9.71133c.9072.90187.9072 2.36417 0 3.26607l-13.426 13.3485c-.9071.9019-2.3779.9019-3.285 0s-.9071-2.3642 0-3.2661l13.4261-13.34847c.9071-.90189 2.3778-.90189 3.2849 0z"/><path d="m27.8187 5.18362c.9077.90128.9087 2.36353.0022 3.26603l-17.2588 17.18215c-.90647.9025-2.37721.9035-3.28495.0022-.90773-.9013-.90872-2.3635-.00221-3.266l17.25876-17.18218c.9065-.9025 2.3772-.90348 3.285-.0022z"/><path d="m10.7276 10.6841c.9056.9035.9031 2.3657-.0056 3.266l-6.75949 6.6974c-.90869.9003-2.37943.8978-3.28499-.0056-.905563-.9035-.903032-2.3657.005654-3.2661l6.759476-6.6973c.90868-.90034 2.37942-.89783 3.28495.0056z" opacity=".7"/><path d="m14.8678 4.00024c1.2828 0 2.3228 1.03397 2.3228 2.30944v.23094c0 1.27546-1.04 2.30943-2.3228 2.30943-1.2829 0-2.3229-1.03397-2.3229-2.30943v-.23094c0-1.27547 1.04-2.30944 2.3229-2.30944z" opacity=".5"/></g></svg>
                @endif
            </a>
            {{-- <div class="flex-grow max-w-2xl">
                <form action="{{ route('scribe.search', ['path' => $currentPath]) }}" method="GET" class="w-full">
                    <input type="text" name="q" placeholder="Search documentation..." class="flex items-center justify-center sm:justify-start md:h-auto md:flex-none md:rounded-lg md:py-2.5 md:pl-4 md:pr-3.5 md:text-sm md:ring-1 md:ring-slate-200 md:hover:ring-slate-300 w-full dark:md:bg-slate-800/75 dark:md:ring-inset dark:md:ring-white/5 dark:md:hover:bg-slate-700/40 dark:md:hover:ring-slate-500">
                </form>
                <div id="search-results" class="absolute z-10 mt-1 w-full bg-dark-800 rounded-md shadow-lg hidden"></div>
            </div> --}}
        </div>
    </nav>

    <!-- Main Content -->
    <div class="flex-grow max-w-7xl w-full mx-auto flex flex-row">
        <!-- Left Sidebar -->
        <aside class="w-64 p-6 border-r border-dark-700 h-screen overflow-y-auto sticky top-0 relative">
            <div class="absolute bottom-0 right-0 top-4 hidden h-12 w-px bg-gradient-to-t from-slate-700 dark:block"></div>
            <div class="absolute bottom-0 right-0 top-16 hidden w-px bg-slate-700 dark:block"></div>
            <nav>
                {!! $sidebar !!}
            </nav>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 p-8 overflow-y-auto">
            <div class="max-w-3xl mx-auto">
                <h1 class="text-3xl text-gray-100 font-bold mb-2">{{ $title }}</h1>
                <div class="prose prose-invert max-w-none markdown-body">
                    {!! $content !!}
                </div>
            </div>
        </main>

        <!-- Right Sidebar - Table of Contents -->
        <aside class="w-64 p-6 h-screen overflow-y-auto sticky top-0">
            <h3 class="text-lg font-semibold mb-4">On this page</h3>
            <nav class="space-y-2 text-sm">
                {!! $tableOfContents !!}
            </nav>
        </aside>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/components/prism-core.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/plugins/autoloader/prism-autoloader.min.js"></script>
    <script>
        // Initialize Prism.js
        Prism.plugins.autoloader.languages_path = 'https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/components/';

        // Highlight active TOC item on scroll
        document.addEventListener('DOMContentLoaded', () => {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        document.querySelectorAll('.toc-item').forEach(item => item.classList.remove('text-blue-400'));
                        const id = entry.target.getAttribute('id');
                        const tocItem = document.querySelector(`.toc-item[href="#${id}"]`);
                        if (tocItem) tocItem.classList.add('text-blue-400');
                    }
                });
            }, { threshold: 0.5 });

            document.querySelectorAll('h1[id], h2[id], h3[id], h4[id], h5[id], h6[id]').forEach(section => {
                observer.observe(section);
            });
        });
    </script>
    <script>
        document.getElementById('search-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const query = this.querySelector('input[name="q"]').value;
            fetch(`${this.action}?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(results => {
                    const resultsContainer = document.getElementById('search-results');
                    resultsContainer.innerHTML = '';
                    resultsContainer.classList.remove('hidden');
                    
                    if (results.length === 0) {
                        resultsContainer.innerHTML = '<p class="p-4 text-gray-400">No results found.</p>';
                        return;
                    }
                    
                    const ul = document.createElement('ul');
                    results.forEach(result => {
                        const li = document.createElement('li');
                        li.innerHTML = `<a href="${result.path}" class="block p-4 hover:bg-dark-700">
                            <h3 class="font-semibold text-white">${result.title}</h3>
                            <p class="text-sm text-gray-400">${result.excerpt}</p>
                        </a>`;
                        ul.appendChild(li);
                    });
                    resultsContainer.appendChild(ul);
                });
        });
        
        // Hide search results when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('#search-form') && !e.target.closest('#search-results')) {
                document.getElementById('search-results').classList.add('hidden');
            }
        });
        </script>
</body>
</html>