<div class="space-y-40">
    @themeComponent('home.sections.hero')

    @themeComponent('home.sections.news', ['articles' => $this->articles])

    @themeComponent('home.sections.essentials')

    @themeComponent('home.sections.beyond-basics')

    @themeComponent('home.sections.catalog')

    @themeComponent('home.sections.cta')

    {{--    <x-home.sections.news :articles="$this->articles"/>--}}

    {{--    <x-home.sections.essentials/>--}}

    {{--    <x-home.sections.beyond-basics/>--}}

    {{--    <x-home.sections.catalog/>--}}

    {{--    <x-home.sections.cta/>--}}
</div>
