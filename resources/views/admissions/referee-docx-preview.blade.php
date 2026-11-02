<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
    <style>
        body { margin: 0; padding: 32px; background: #f5f5f5; color: #222; font: 15px/1.65 Arial, sans-serif; }
        .document { max-width: 820px; min-height: calc(100vh - 64px); margin: 0 auto; padding: 48px 56px; box-sizing: border-box; background: #fff; box-shadow: 0 1px 5px rgba(0, 0, 0, .18); }
        p { margin: 0 0 1em; white-space: pre-wrap; }
        .empty { color: #666; font-style: italic; }
        @media (max-width: 700px) { body { padding: 0; } .document { min-height: 100vh; padding: 28px 22px; } }
    </style>
</head>
<body>
    <main class="document">
        @forelse($paragraphs as $paragraph)
            <p>{{ $paragraph }}</p>
        @empty
            <p class="empty">This DOCX file contains no readable text. Use Download above to open the original document.</p>
        @endforelse
    </main>
</body>
</html>