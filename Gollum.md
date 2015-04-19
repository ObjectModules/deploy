# Gollum

[Gollum](http://github.com/github/gollum/) is a simple, Git-powered wiki with a sweet API and local frontend.

## Repo Structure

A Gollum repository's contents are designed to be human editable. Page content
is written in `page files` and may be organized into directories any way you
choose. Special footers can be created in `footer files`. Other content
(images, PDFs, etc) may also be present and organized in the same way.

## Page Files

Page files may be written in any format supported by
[GitHub-Markup](http://github.com/github/markup) (except roff). By default,
Gollum recognizes the following extensions:

* ASCIIDoc: .asciidoc
* Creole: .creole
* Markdown: .markdown, .mdown, .mkdn, .mkd, .md
* Org Mode: .org
* Pod: .pod
* RDoc: .rdoc
* ReStructuredText: .rest.txt, .rst.txt, .rest, .rst
* Textile: .textile
* MediaWiki: .mediawiki, .wiki

You may also register your own extensions and parsers:

```ruby
Gollum::Markup.register(:angry, "Angry") do |content|
  content.upcase
end
```

Gollum detects the page file format via the extension, so files must have one
of the default or registered extensions in order to be converted.

Page file names may contain any printable UTF-8 character except space
(U+0020) and forward slash (U+002F). If you commit a page file with any of
these characters in the name it will not be accessible via the web interface.

Even though page files may be placed in any directory, there is still only a
single namespace for page names, so all page files should have globally unique
names regardless of where they are located in the repository.

The special page file `Home.ext` (where the extension is one of the supported
formats) will be used as the entrance page to your wiki. If it is missing, an
automatically generated table of contents will be shown instead.

## Sidebar Files

Sidebar files allow you to add a simple sidebar to your wiki. Sidebar files
are named `_Sidebar.ext` where the extension is one of the supported formats.
Sidebars affect all pages in their directory and any subdirectories that do not
have a sidebar file of their own.

## Header Files

Header files allow you to add a simple header to your wiki. Header files must
be named `_Header.ext` where the extension is one of the supported formats.
Like sidebars, headers affect all pages in their directory and any
subdirectories that do not have a header file of their own.

## Footer Files

Footer files allow you to add a simple footer to your wiki. Footer files must
be named `_Footer.ext` where the extension is one of the supported formats.
Like sidebars, footers affect all pages in their directory and any
subdirectories that do not have a footer file of their own.

## Html Sanitization

For security and compatibility reasons Gollum wikis may not contain custom CSS
or JavaScript. These tags will be stripped from the converted HTML. See
`docs/sanitization.md` for more details on what tags and attributes are
allowed.

## Titles

The first defined `h1` will override the default header on a page. There are
two ways to set a page title. The metadata syntax:

    <!-- --- title: New Title -->

The first `h1` tag can be set to always override the page title, without
needing to use the metadata syntax. Start gollum with the `--h1-title` flag.

## Bracket Tags

A variety of Gollum tags use a double bracket syntax. For example:

```
[[Link]]
```

Some tags will accept attributes which are separated by pipe symbols. For
example:

```
[[Link|Page Title]]
```

In all cases, the first thing in the link is what is displayed on the page.
So, if the tag is an internal wiki link, the first thing in the tag will be
the link text displayed on the page. If the tag is an embedded image, the
first thing in the tag will be a path to an image file. Use this trick to
easily remember which order things should appear in tags.

Some formats, such as MediaWiki, support the opposite syntax:

```
[[Page Title|Link]]
```

## Page Links

To link to another Gollum wiki page, use the Gollum Page Link Tag.

```
[[Frodo Baggins]]
```

The above tag will create a link to the corresponding page file named
`Frodo-Baggins.ext` where `ext` may be any of the allowed extension types. The
conversion is as follows:

1. Replace any spaces (U+0020) with dashes (U+002D)
2. Replace any slashes (U+002F) with dashes (U+002D)

If you'd like the link text to be something that doesn't map directly to the
page name, you can specify the actual page name after a pipe:

```
[[Frodo|Frodo Baggins]]
```

The above tag will link to `Frodo-Baggins.ext` using "Frodo" as the link text.

The page file may exist anywhere in the directory structure of the repository.
Gollum does a breadth first search and uses the first match that it finds.

Here are a few more examples:

```
[[J. R. R. Tolkien]] -> J.-R.-R.-Tolkien.ext
[[Movies / The Hobbit]] -> Movies---The-Hobbit.ext
[[モルドール]] -> モルドール.ext
```

## External Links

As a convenience, simple external links can be placed within brackets and they
will be linked to the given URL with the URL as the link text. For example:

```text
[[http://example.com]]
```

External links must begin with either "http://" or "https://". If you need
something more flexible, you can resort to the link syntax in the page's
underlying markup format.


## Absolute vs. Relative vs. External path

For Gollum tags that operate on static files (images, PDFs, etc), the paths
may be referenced as either relative, absolute, or external. Relative paths
point to a static file relative to the page file within the directory
structure of the Gollum repo (even though after conversion, all page files
appear to be top level). These paths are NOT prefixed with a slash. For
example:

    gollum.pdf
    docs/diagram.png

Absolute paths point to a static file relative to the Gollum repo's
root, regardless of where the page file is stored within the directory
structure. These paths ARE prefixed with a slash. For example:

    /pdfs/gollum.pdf
    /docs/diagram.png

External paths are full URLs. An external path must begin with either
"http://" or "https://". For example:

    http://example.com/pdfs/gollum.pdf
    http://example.com/images/diagram.png

All of the examples in this README use relative paths, but you may use
whatever works best in your situation.


## File Links

To link to static files that are contained in the Gollum repository you should
use the Gollum File Link Tag.

```
[[Gollum|gollum.pdf]]
```

The first part of the tag is the link text. The path to the file appears after
the pipe.


## Images

To display images that are contained in the Gollum repository you should use
the Gollum Image Tag. This will display the actual image on the page.

```
[[gollum.png]]
```

In addition to the simple format, there are a variety of options that you
can specify between pipe delimiters.

To specify alt text, use the `alt=` option. Default is no alt text.

```
[[gollum.png|alt=Gollum and his precious wiki]]
```

To place the image in a frame, use the `frame` option. When combined with the
`alt=` option, the alt text will be used as a caption as well. Default is no
frame.

```
[[gollum.png|frame|alt=Gollum and his precious wiki]]
```

To specify the alignment of the image on the page, use the `align=` option.
Possible values are `left`, `center`, and `right`. Default is `left`.

```
[[gollum.png|align=center]]
```

To float an image so that text flows around it, use the `float` option. When
`float` is specified, only `left` and `right` are valid `align` options.
Default is not floating. When floating is activated but no alignment is
specified, default alignment is `left`.

```
[[gollum.png|float]]
```

By default text will fill up all the space around the image. To control how
much should show up use this tag to stop and start a new block so that
additional content doesn't fill in.

```
[[_]]
```

To specify a max-width, use the `width=` option. Units must be specified in
either `px` or `em`.

```
[[gollum.png|width=400px]]
```

To specify a max-height, use the `height=` option. Units must be specified in
either `px` or `em`.

```
[[gollum.png|height=300px]]
```

Any of these options may be composed together by simply separating them with
pipes.


## Escaping Gollum Tags

If you need the literal text of a wiki or static link to show up in your final
wiki page, simply preface the link with a single quote (like in LISP):

```
'[[Page Link]]
'[[File Link|file.pdf]]
'[[image.jpg]]
```

This is useful for writing about the link syntax in your wiki pages.

## Table Of Contents

Gollum has a special tag to insert a table of contents (new in v2.1)

```
[[_TOC_]]
```

This tag is case sensitive, use all upper case.  The TOC tag can be inserted
into the `_Header`, `_Footer` or `_Sidebar` files too.

There is also a wiki option `:universal_toc` which will display a
table of contents at the top of all your wiki pages if it is enabled.
The `:universal_toc` is not enabled by default.  To set the option,
add the option to the `:wiki_options` hash before starting the
frontend app:

```ruby
Precious::App.set(:wiki_options, {:universal_toc => true})
```

## Syntax Highlighting

In page files you can get automatic syntax highlighting for a wide range of
languages (courtesy of [Pygments](http://pygments.org/) - must install
separately) by using the following syntax:

```
    ```ruby
      def foo
        puts 'bar'
      end
    ```
```

The block must start with three backticks, at the beginning of a line or
indented with any number of spaces or tabs.
After that comes the name of the language that is contained by the
block. The language must be one of the `short name` lexer strings supported by
Pygments. See the [list of lexers](http://pygments.org/docs/lexers/) for valid
options.

The block contents should be indented at the same level than the opening backticks.
If the block contents are indented with an additional two spaces or one tab,
then that whitespace will be ignored (this makes the blocks easier to read in plaintext).

The block must end with three backticks indented at the same level than the opening
backticks.

### GitHub Syntax Highlighting

As an extra feature, you can syntax highlight a file from your repository, allowing
you keep some of your sample code in the main repository. The code-snippet is
updated when the wiki is rebuilt. You include GitHub code like this:

```
    ```html:github:gollum/gollum/master/test/file_view/1_file.txt```
```

This will make the builder look at the **gollum user**, in the **gollum project**,
in the **master branch**, at path **test/file_view/1_file.txt**. It will be
rewritten to:

```
    ```html
    <ol class="tree">
      <li class="file"><a href="0">0</a></li>
    </ol>
    ```
```

Which will be parsed as HTML code during the Pygments run, and thereby coloured
appropriately.

## Mathematical Equations

Start gollum with the `--mathjax` flag. Read more about [MathJax](http://docs.mathjax.org/en/latest/index.html) on the web. Gollum uses the `TeX-AMS-MML_HTMLorMML` config with the `autoload-all` extension.

Inline math:

```text
\\\(2^2\\\)
```

Display math:

```text
$$2^2$$
\\\[2^2\\\]
```

## Sequence Diagrams

You may embed sequence diagrams into your wiki page (rendered by
[WebSequenceDiagrams](http://www.websequencediagrams.com) by using the
following syntax:

    {{{{{{ blue-modern
      alice->bob: Test
      bob->alice: Test response
    }}}}}}

You can replace the string "blue-modern" with any supported style.


## Include other pages

You may include other pages by using the following syntax, where _pagename_ is specified exactly like a link.  But instead of a link, gollum will copy in the page's contents:

```
    [[include:pagename]]
```