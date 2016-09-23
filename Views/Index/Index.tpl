
@{foreach library    namespace => classes}
    <h1>@{namespace}</h1>
    <ul>
    @{foreach classes idx => class}
        <li>@{condition class.url > "base test"?"hi":"hello" }</li>
    @{/foreach}
    </ul>
@{/foreach}
