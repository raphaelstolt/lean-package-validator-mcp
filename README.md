# Lean package validator MCP

![Test Status](https://github.com/raphaelstolt/lean-package-validator-mcp/workflows/ci/badge.svg)
[![Version](http://img.shields.io/packagist/v/stolt/lean-package-validator-mcp.svg?style=flat)](https://packagist.org/packages/stolt/lean-package-validator-mcp)
![Downloads](https://img.shields.io/packagist/dt/stolt/lean-package-validator-mcp)
![PHP Version](https://img.shields.io/badge/php-8.2+-ff69b4.svg)
[![PDS Skeleton](https://img.shields.io/badge/pds-skeleton-blue.svg?style=flat)](https://github.com/php-pds/skeleton)
[![Lean dist package](https://img.shields.io/badge/lean-dist%20package-00ffb6.svg?style=flat)](https://github.com/raphaelstolt/lean-package-validator-mcp)

A framework-agnostic Model Context Protocol (MCP) server for [lean-package-validator](https://github.com/raphaelstolt/lean-package-validator). 

This package exposes tools to manage and validate `.gitattributes` files and Git archives, helping to maintain clean package exports.

## Available tools

The MCP server exposes the following tools:

- `lean_package_validator_mcp`: Run arbitrary `lean-package-validator` commands.
- `generate_gitattributes`: Create a new `.gitattributes` file for a project/micro-package repository.
- `update_gitattributes`: Update an existing `.gitattributes` file for a project/micro-package repository.
- `validate_package_archive`: Validate the `.gitattributes` file of a given project/micro-package repository, including Git archive validation against HEAD.

## Installation

You can install this package via Composer:

```bash
composer create-project stolt/lean-package-validator-mcp
```

## Setup in MCP clients

Add the server to your MCP client configuration (e.g., Cursor, Claude Desktop):

```json
{
  "mcpServers": {
    "lean-package-validator": {
      "command": "php",
      "args": ["/path/to/lean-package-validator-mcp/bin/server.php"]
    }
  }
}
```

## Contributing

If you're considering contributing to this project, have a look at this repository's [CONTRIBUTING.md](.github/CONTRIBUTING.md)
for more advice.

### License

This project is licensed under the MIT license. Please see [LICENSE.md](LICENSE.md) for more details.
