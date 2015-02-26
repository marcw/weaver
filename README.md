# Weaver

Weaves HTML blocks and fragments together.

```
<?php

$weaver = new \MarcW\Weaver\Weaver();
$body = '<p>foobar</p><p>barfoo</p>';
$fragments = ['<img src="/foobar.png" />'];
$result = $weaver->weave($body, $fragments);
// $results contains: <p>foobar</p><img src="/foobar.png" /><p>barfoo</p>
```

It's also possible to implicity tells the library where to weaves the HTML pieces.

```
<?php

$weaver = new \MarcW\Weaver\Weaver();
$body = '<p>foobar</p><p>barfoo</p>__WEAVE__';
$fragments = ['<img src="/foobar.png" />'];
$result = $weaver->weave($body, $fragments);
// $results contains: <p>foobar</p><p>barfoo</p><img src="/foobar.png" />
```

## License and Copyright

See LICENSE file in this repository
