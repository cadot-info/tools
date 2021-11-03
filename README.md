# tools

## Functions

### returnAllLinks

Return a array of url links of page and sub-page
-string $start url of strat seek
-int $descent number or sub links
-array $urlTwoPoints extract before : , example mailto, htpp..., default: 'mailto', 'http', 'https'
-array $urlPoint extract before first point , example www, default 'www'
-array $classRefuse don't take link if class is in this array. It's for js for example
-array|boolean $client send a client if you have login for example
-array $links for recursivity

### E

Return a immediatly message
-E(message)

## Utilisation

use by traits

```php
use App\Tests\Tools;

class ClientTest extends PantherTestCase
{
   use Tools;
```
