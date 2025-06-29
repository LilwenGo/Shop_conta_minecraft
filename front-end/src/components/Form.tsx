import type React from "react";
import { motion } from "motion/react";
import Input from "./Input";
import { useEffect, useMemo, useState } from "react";

export type InputDefinition = {
    name: string,
    label: string,
    type: string,
    hidden?: boolean,
    initialValue?: string,
    options?: Array<{
        name: string,
        value?: string
    }>,
    rules: Array<{
        regex: RegExp,
        message: string
    }>
};

type FormParams = {
    title: string,
    description?: string | React.ReactNode,
    inputs: Array<InputDefinition | false>,
    className?: string,
    callBack: CallableFunction,
    children?: React.ReactNode
};

export default function Form({title, description, inputs, className, callBack, children}: FormParams) {
    function handleSubmit(e: React.FormEvent) {
        e.preventDefault();
        const hasErrors = Object.entries(formState).some(
            (field: any) => {
                const name = field[0];
                const value = field[1];
                const input = filteredInputs.find((i) => i.name === name)?.rules ?? [];
                if(typeof value.value === 'string') {
                    value.value = value.value.trim();
                }
                return value.errors.length > 0 || (input.length > 0 && value.value == "");
            }
        );
        if(hasErrors) {
            return;
        } else {
            return callBack(formState, setFormState);
        }
    }

    const filteredInputs = useMemo(() => inputs.filter(Boolean) as InputDefinition[], [inputs]);

    const [formState, setFormState] = useState(() => {
        const initial: Record<string, { value: string; errors: string[] }> = {};
        for (const input of filteredInputs) {
            if(input) initial[input.name] = { value: input.initialValue ?? '', errors: [] };
        }
        return initial;
    });

    useEffect(() => {
        const initial: Record<string, { value: string; errors: string[] }> = {};
        for (const input of filteredInputs) {
            if (input) {
                initial[input.name] = { value: input.initialValue ?? '', errors: [] };
            }
        }
        setFormState(initial);
    }, [filteredInputs]);

    const hasErrors = Object.entries(formState).some(
        (field: any) => {
            const name = field[0];
            const value = field[1];
            const input = filteredInputs.find((i) => i.name === name)?.rules ?? [];
            if(typeof value.value === 'string') {
                value.value = value.value.trim();
            }
            return value.errors.length > 0 || (input.length > 0 && value.value == "");
        }
    );

    const hasVisibleFields = Object.values(filteredInputs).some(
        (field: any) => !field.hidden
    );
    
    return (
        <form action="" className={`form ${className}`} onSubmit={handleSubmit} encType="multipart/form-data">
            <h2 className="subtitle">{title}</h2>
            {description && <p className="paragraph">{description}</p>}
            {filteredInputs.map((i: InputDefinition | false, index: number) => {
                if(!i) return <></>;
                return (
                    <Input 
                        key={`input-${index}`} 
                        type={i.type} 
                        name={i.name} 
                        label={i.label} 
                        rules={i.rules}
                        hidden={i.hidden ?? false}
                        options={i.options ?? undefined}
                        formState={formState}
                        setFormState={setFormState}
                    />
                );
            })}
            <div className="bubble-group">
                {children}
                {
                    hasVisibleFields && <motion.input 
                        whileHover={{scale: 1.05, transition: {duration: 0.15}}}
                        whileTap={{scale: 0.95, transition: {duration: 0.05}}}
                        type="submit" 
                        className="btn btn-primary" 
                        value="Valider" 
                        disabled={hasErrors}
                    />
                }
            </div>
        </form>
    );
}