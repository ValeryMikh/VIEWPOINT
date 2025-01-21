# REPOSITORY VIEWPOINT — the basis of data storage:
REPOSITORY is the key component of VIEWPOINT responsible for physical data storage. It implements a columnar storage model, providing high performance for analytical queries.
## Key features of REPOSITORY:
* __Columnar data storage:__ Data is stored in columns, not in rows, as in traditional relational databases. This provides high efficiency when performing analytical queries, such as aggregation, filtering and searching.
* __Universal data representation:__ Each column can contain data of various types:
    * __Simple data types:__ Numbers, strings, dates, etc. in the "code-value" format.
    * __Complex data types:__ Arrays, structures.
    * __Relationship types:__ Information about relationships between data.
    * __VIEW elements:__ Links to other VIEW elements.
* __Automatic structure creation:__ The structure of the physical database (data tables and relationships) is predefined and created automatically by the VIEWPOINT utility. This ensures a consistent structure across all VIEWPOINT databases and simplifies the deployment process.
* __Normalized structure:__ The VIEWPOINT database is well normalized, which minimizes data redundancy and ensures integrity.
* __User transparency:__ The user interacts with the data through VIEW elements and ViewQL, without accessing the REPOSITORY directly. This simplifies development and allows you to focus on business logic.
* __Real-time database creation:__ The physical structure of the database in the REPOSITORY is created dynamically as VIEWs and their relationships are defined. This means that there is no need to pre-define the database schema.

On this website, you will find detailed documentation, tutorials, and resources to help you experience the transformative power VIEWPOINT.

## Website [VIEWPOINT](https://sites.google.com/view/myviewpoint)


### Copyright ©. V. Mikhailovski. All rights reserved
