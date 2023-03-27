# Responders

Responders respond data: transform data to a Symfony response, return a success in a CLI operation.

<!-- TOC -->
* [Default responders](#default-responders)
<!-- TOC -->

## Default responders

When your operation is an instance of `Sylius\Component\Resource\Metadata\HttpOperation` two responders are configured by default.

The responder will automatically choose the responder depending on the request format:

| Request format | Responder                                                     |
|----------------|---------------------------------------------------------------|
| html           | Sylius\Component\Resource\Symfony\Request\State\TwigResponder |
| json           | Sylius\Component\Resource\Symfony\Request\State\ApiResponder  |
| xml            | Sylius\Component\Resource\Doctrine\Common\State\ApiResponder  |

**[Go back to the documentation's index](index.md)**
