<div {{ $attributes->merge(['class' => '
   prose dark:prose-invert max-w-none

   [&_h1]:text-2xl [&_h1]:font-medium [&_h1]:mb-6 [&_h1]:text-zinc-800 dark:[&_h1]:text-white
   [&_h2]:text-lg [&_h2]:font-medium [&_h2]:mb-4 [&_h2]:text-zinc-800 dark:[&_h2]:text-white
   [&_h3]:text-base [&_h3]:font-medium [&_h3]:mb-3 [&_h2]:text-zinc-800 dark:[&_h2]:text-white

   [&_p]:mb-4 [&_p]:text-sm [&_p]:leading-relaxed [&_p]:text-zinc-500 dark:[&_p]:text-white/70
   [&_strong]:font-bold [&_strong]:text-sm [&_strong]:text-zinc-500 dark:[&_strong]:text-white/90
   [&_em]:italic

   [&_ul]:list-disc [&_ul]:text-sm [&_ul]:ml-6 [&_ul]:mb-4
   [&_ol]:list-decimal [&_ol]:text-sm [&_ol]:ml-6 [&_ol]:mb-4
   [&_li]:mb-2 [&_li]:text-sm [&_li]:text-zinc-500 dark:[&_li]:text-white/70

   [&_blockquote]:border-l-4 [&_blockquote]:border-zinc-300 [&_blockquote]:pl-4
   [&_blockquote]:italic [&_blockquote]:text-sm [&_blockquote]:text-zinc-500 dark:[&_blockquote]:text-white/70

   [&_pre]:p-6 [&_pre]:my-4 [&_pre]:text-sm [&_pre]:rounded-xl [&_pre]:bg-white dark:[&_pre]:bg-white/10
   [&_pre]:border [&_pre]:border-zinc-200 dark:[&_pre]:border-white/10
   [&_pre]:text-zinc-500 dark:[&_pre]:text-white/70 [&_pre]:whitespace-pre-wrap [&_pre]:break-words
   [&_code]:font-mono [&_code]:text-sm

   [&_a]:inline [&_a]:font-medium [&_a]:underline [&_a]:underline-offset-[6px]
   [&_a]:text-zinc-800 dark:[&_a]:text-white [&_a]:decoration-zinc-800/20 dark:[&_a]:decoration-white/20
   hover:[&_a]:decoration-current

   [&_hr]:my-8 [&_hr]:border-800/15 dark:[&_hr]:border-white/20
']) }}>
    {!! str_replace('&nbsp;', ' ', $content) !!}
</div>
