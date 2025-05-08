PROMPT 'Creating constraints'

CREATE UNIQUE INDEX i_prds_id ON
    products (
        prds_id
    );
ALTER TABLE products ADD (
    CONSTRAINT pk_prds_id PRIMARY KEY ( prds_id )
);

CREATE UNIQUE INDEX i_empl_id ON
    employees (
        empl_id
    );
ALTER TABLE employees ADD (
    CONSTRAINT pk_empl_id PRIMARY KEY ( empl_id )
);

CREATE UNIQUE INDEX i_eqpt_id ON
    equipment (
        eqpt_id
    );
ALTER TABLE equipment ADD (
    CONSTRAINT pk_eqpt_id PRIMARY KEY ( eqpt_id )
);

CREATE UNIQUE INDEX i_dfct_id ON
    defects (
        dfct_id
    );
ALTER TABLE defects ADD (
    CONSTRAINT pk_dfct_id PRIMARY KEY ( dfct_id )
);

CREATE UNIQUE INDEX i_comp_id ON
    components (
        comp_id
    );
ALTER TABLE components ADD (
    CONSTRAINT pk_comp_id PRIMARY KEY ( comp_id )
);

CREATE UNIQUE INDEX i_mat_id ON
    materials (
        mat_id
    );
ALTER TABLE materials ADD (
    CONSTRAINT pk_mat_id PRIMARY KEY ( mat_id )
);

CREATE UNIQUE INDEX i_docs_id ON
    documents (
        docs_id
    );
ALTER TABLE documents ADD (
    CONSTRAINT pk_docs_id PRIMARY KEY ( docs_id )
);

ALTER TABLE products ADD (
    CONSTRAINT c_prds_eqpt_id FOREIGN KEY ( prds_eqpt_id )
        REFERENCES equipment
);

ALTER TABLE products ADD (
    CONSTRAINT c_prds_empl_id FOREIGN KEY ( prds_empl_id )
        REFERENCES employees
);

ALTER TABLE products ADD (
    CONSTRAINT c_prds_docs_id FOREIGN KEY ( prds_docs_id )
        REFERENCES documents
);

ALTER TABLE products ADD (
    CONSTRAINT c_prds_dfct_id FOREIGN KEY ( prds_dfct_id )
        REFERENCES defects
);

COMMIT;