<p align="center">
    <a href="https://github.com/turnmark/turnmark">
        <img src="assets/logos/turnmark_01.png" alt="Turnmark Logo">
    </a>
</p>

Turnmark は、ボートレース（ 競艇 ）関連のパッケージを開発するプロジェクトです。

各パッケージは独立した GitHub リポジトリへ分割されており、Packagist から個別にインストールできます。

## 📦 パッケージ

| Package | Description | Packagist |
|---------|-------------|-----------|
| `turnmark/scraper` | ボートレース公式サイトのスクレイピング | https://packagist.org/packages/turnmark/scraper |
| `turnmark/scraper-fukuoka` | ボートレース福岡公式サイトのスクレイピング | https://packagist.org/packages/turnmark/scraper-fukuoka |
| `turnmark/scraper-mikuni` | ボートレース三国公式サイトのスクレイピング | https://packagist.org/packages/turnmark/scraper-mikuni |
| `turnmark/scraper-tamagawa` | ボートレース多摩川公式サイトのスクレイピング | https://packagist.org/packages/turnmark/scraper-tamagawa |
| `turnmark/scraper-tokuyama` | ボートレース徳山公式サイトのスクレイピング | https://packagist.org/packages/turnmark/scraper-tokuyama |

## 🗂️ リポジトリ構造

```
.
├── assets/
│   └── logos/
├── packages/
│   ├── scraper/
│   ├── scraper-fukuoka/
│   ├── scraper-mikuni/
│   ├── scraper-tamagawa/
│   └── scraper-tokuyama/
├── .editorconfig
├── .gitignore
├── CHANGELOG.md
├── composer.json
├── LICENSE
├── phpunit.xml.dist
├── psalm.xml.dist
└── README.md
```

各ディレクトリは独立した Composer パッケージです。

リリース時には、それぞれ専用リポジトリへ同期され Packagist へ公開されます。

## 📄 ライセンス

Turnmark は [MIT license](LICENSE) の元で公開されています。
