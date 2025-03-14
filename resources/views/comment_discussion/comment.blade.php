<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Diskusfy</title>
  <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.1/dist/flowbite.min.css" rel="stylesheet" />
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gray-900 dark:bg-gray-900">
  <section class="bg-gray-900 dark:bg-gray-900 py-8 lg:py-16 antialiased">
    <div class="mx-auto max-w-screen-lg px-4 2xl:px-0">
      <div class="flex justify-between items-center">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $diskusi->judul }}</h2>
        <a href="/"
          class="inline-flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-blue-500">
          <svg class="w-5 h-5 mr-2" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
            <path d="M12 2L10.59 3.41 15.17 8H3v2h12.17l-4.58 4.59L12 18l8-8-8-8z"></path>
          </svg>
          Back to Home
        </a>
      </div>

      <div class="mt-2 border-t border-gray-700 pt-2">
        <div class="bg-gray-100 dark:bg-gray-800 p-4 rounded-lg flex items-start mt-3">
          <p class="text-gray-900 dark:text-gray-300">{{ $diskusi->isi_diskusi }}</p>
        </div>
      </div>

      <form id="commentForm" method="POST" action="/comment">
        @csrf
        <div class="py-2 px-4 mb-4 bg-white rounded-lg border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
          <label for="isi_balasan" class="sr-only">Your comment</label>
          <textarea name="isi_balasan" id="isi_balasan" rows="6"
            class="px-0 w-full text-sm text-gray-900 border-0 focus:ring-0 focus:outline-none dark:text-white dark:placeholder-gray-400 dark:bg-gray-800"
            placeholder="Write a comment..." required></textarea>
        </div>
        <button type="submit"
          class="inline-flex bg-green-700 items-center py-2.5 px-4 text-xs font-medium text-center text-white rounded-lg focus:ring-4 focus:ring-primary-200 dark:focus:ring-primary-900 hover:bg-primary-800">
          Post comment
        </button>
      </form>

      <section class="mt-8">
        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
          Comments ({{ count($diskusi->balasans) }})
        </h3>

        @foreach ($diskusi->balasans as $balasan)
      <article class="mt-4 p-6 text-base bg-white rounded-lg shadow dark:bg-gray-900">
        <footer class="flex justify-between items-center mb-2">
        <div class="flex items-center">
          <img class="mr-2 w-6 h-6 rounded-full"
          src="https://ui-avatars.com/api/?name={{ urlencode($balasan->pengguna->username) }}"
          alt="{{ $balasan->pengguna->username }}">
          <p class="inline-flex items-center mr-3 text-sm text-gray-900 dark:text-white font-semibold">
          {{ $balasan->pengguna->username }}
          </p>
          <p class="text-sm text-gray-600 dark:text-gray-400">
          <time pubdate datetime="{{ $balasan->created_at }}"
            title="{{ $balasan->created_at->format('d M Y, H:i') }}">
            {{ $balasan->created_at->format('d M Y, H:i') }}
          </time>
          </p>
        </div>
        <button id="deleteButton{{ $balasan->id_balasan }}" data-comment-uid="{{ $balasan->uid }}"
          class="inline-flex items-center p-2 text-sm font-medium text-center text-red-500 dark:text-red-400 bg-white rounded-lg hover:bg-red-100 focus:ring-4 focus:outline-none focus:ring-red-50 dark:bg-gray-900 dark:hover:bg-red-700 dark:focus:ring-red-600 delete-button"
          type="button" onclick="deleteComment({{ $balasan->id_balasan }})">
          <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
          viewBox="0 0 20 20">
          <path fill-rule="evenodd"
            d="M6 8a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V8Zm3-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v1h4a1 1 0 1 1 0 2H3a1 1 0 1 1 0-2h4V3Z"
            clip-rule="evenodd"></path>
          </svg>
        </button>
        </footer>
        <p class="text-gray-500 dark:text-gray-400">
        {{ $balasan->isi_balasan }}
        </p>

        <div class="flex items-center mt-5 space-x-4">
        <button id="likeButton{{ $balasan->id_balasan }}" onclick="toggleLike({{ $balasan->id_balasan }})"
          class="like-button flex items-center space-x-1 focus:outline-none">
          <div class="p-2 rounded-full transition-colors duration-200 hover:bg-red-500">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
            stroke="currentColor" class="w-5 h-5 text-gray-400">
            <path stroke-linecap="round" stroke-linejoin="round"
            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
          </svg>
          </div>
          <span class="text-white" id="likeCount{{ $balasan->id_balasan }}">
          {{ $balasan->votes->count() }}
          </span>
        </button>
        <a href="#"
          class="flex items-center text-sm text-gray-500 hover:underline dark:text-gray-400 font-medium reply-toggle"
          data-comment-id="{{ $balasan->id_balasan }}">
          Reply
        </a>
        </div>

        <div id="replyForm{{ $balasan->id_balasan }}" class="reply-form hidden mt-4">
        <form method="POST" action="/reply/{{ $balasan->id_balasan }}">
          @csrf
          <div
          class="py-2 px-4 mb-4 bg-white rounded-lg border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
          <label for="reply_{{ $balasan->id_balasan }}" class="sr-only">Your reply</label>
          <textarea name="isi_balasan2" id="reply_{{ $balasan->id_balasan }}" rows="3"
            class="px-0 w-full text-sm text-gray-900 border-0 focus:ring-0 focus:outline-none dark:text-white dark:placeholder-gray-400 dark:bg-gray-800"
            placeholder="Write your reply..." required></textarea>
          </div>
          <button type="submit"
          class="inline-flex bg-blue-700 items-center py-2.5 px-4 text-xs font-medium text-center text-white rounded-lg focus:ring-4 focus:ring-primary-200 dark:focus:ring-primary-900 hover:bg-primary-800">
          Post reply
          </button>
        </form>
        </div>

        @if($balasan->replies->isNotEmpty())
      <div class="ml-12 mt-4">
      @foreach ($balasan->replies as $reply)
      <article class="mt-4 p-4 bg-white rounded-lg shadow dark:bg-gray-800">
      <footer class="flex justify-between items-center mb-2">
      <div class="flex items-center">
      <img class="mr-2 w-6 h-6 rounded-full"
      src="https://ui-avatars.com/api/?name={{ urlencode($reply->pengguna->username) }}"
      alt="{{ $reply->pengguna->username }}">
      <p class="inline-flex items-center mr-3 text-sm text-gray-900 dark:text-white font-semibold">
      {{ $reply->pengguna->username }}
      </p>
      <p class="text-sm text-gray-600 dark:text-gray-400">
      <time pubdate datetime="{{ $reply->created_at }}"
        title="{{ $reply->created_at->format('d M Y, H:i') }}">
        {{ $reply->created_at->format('d M Y, H:i') }}
      </time>
      </p>
      </div>
      <button id="deleteReplyButton{{ $reply->id_balasan2 }}" data-reply-uid="{{ $reply->uid }}"
      class="inline-flex items-center p-2 text-sm font-medium text-center text-red-500 dark:text-red-400 bg-white dark:bg-gray-800 rounded-lg hover:bg-red-100 focus:ring-4 focus:outline-none focus:ring-red-50 dark:hover:bg-red-700 dark:focus:ring-red-600 delete-reply-button"
      type="button" onclick="deleteReply({{ $reply->id_balasan2 }})">
      <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
      viewBox="0 0 20 20">
      <path fill-rule="evenodd"
        d="M6 8a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V8Zm3-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v1h4a1 1 0 1 1 0 2H3a1 1 0 1 1 0-2h4V3Z"
        clip-rule="evenodd"></path>
      </svg>
      </button>
      </footer>
      <p class="text-gray-500 dark:text-gray-400">
      {{ $reply->isi_balasan2 }}
      </p>
      </article>
    @endforeach
      </div>
    @endif
      </article>
    @endforeach
      </section>
    </div>
  </section>

  <script>
    const diskusiId = "{{ $diskusi->id_diskusi }}";
    console.log("diskusiId:", diskusiId);
  </script>
  <script src="{{ asset('js/deleteComment.js') }}" type="module"></script>
  <script src="{{ asset('js/deleteReply.js') }}" type="module"></script>
  <script src="{{ asset('js/voteButton.js') }}" type="module"></script>
  <script src="{{ asset('js/replyButton.js') }}" type="module"></script>
  <script src="{{ asset('js/balasanuid.js') }}" type="module"></script>
  <script src="{{ asset('js/replyuid.js') }}" type="module"></script>
  <script src="{{ asset('js/voteuid.js') }}" type="module"></script>
  <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.1/dist/flowbite.min.js"></script>
</body>

</html>