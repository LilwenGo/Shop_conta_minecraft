import { motion } from "motion/react";
import { useState } from "react";

export default function Input({type, name, label, hidden, rules, options, formState, setFormState}: {type: string, name: string, label: string, hidden: boolean, rules: Array<{regex: RegExp, message: string}>, options?: Array<{name: string, value?: string}>, formState: any, setFormState: CallableFunction}) {
    function handleChange(name: string, value: string) {
        let errors = [];
        for(let rule of rules) {
            if(!rule.regex.test(value.trim())) {
                errors.push(rule.message);
            };
        }
        setErrors(errors);
        setFormState({
            ...formState,
            [name]: {value, errors}
        });
    }

    const [errors, setErrors] = useState([] as string[]);
    const data = formState[name] as {
        value: string,
        errors: string[]
    };

    if(!data) return <></>;

    return (
        <motion.div
            className={`input ${errors.length > 0 ? "error": ''}  ${hidden ? 'hide': ''}`}
            whileHover={{scale: 1.05, transition: {duration: 0.15}}}
        >
            <label htmlFor={`${name}-input`}>{label}</label>
            {type === 'select' ? 
                <select onChange={(e) => handleChange(name, e.target.value)} hidden={hidden} id={`${name}-input`} name={name} value={data.value}>
                    <option value="">Choisissez une option</option>
                    {options?.map((o) => {
                        return (<option value={o.value ?? o.name}>{o.name}</option>);
                    })}
                </select>
                : <input onChange={(e) => handleChange(name, e.target.value)} type={type} hidden={hidden} id={`${name}-input`} name={name} value={data.value} />
            }
            <span className="small">{errors.length > 0 && errors[0]}</span>
        </motion.div>
    );
}